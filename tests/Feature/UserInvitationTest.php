<?php

use App\Events\Auth\Invited;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Auth\Events\Verified;

test('an existing user can invite a new user', function (): void {
    // inviting a new user should create both a user and an invitation
    Event::fake();

    $invitingUser = User::factory()->create();

    $response = $this->actingAs($invitingUser)->postJson('/api/users/invite', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@example.com',
        'locale' => 'fr',
    ]);

    $response->assertCreated();
    $response->assertJson([
        'data' => [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'locale' => 'fr',
        ]]);

    Event::assertDispatched(Invited::class, function ($event): bool {
        ray($event);
        if ($event->password === null) {
            return false;
        }

        return $event->invitation->user->email === 'john.doe@example.com';
    });
});

test('a user cannot be invited with an invalid email domain', function (): void {

    $invitingUser = User::factory()->create();

    $response = $this->actingAs($invitingUser)->postJson('/api/users/invite', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@wrong.com',
    ]);

    $response->assertStatus(422);
});

test('a user cannot invited a user that already exists', function (): void {
    // inviting a new user should create both a user and an invitation

    $invitingUser = User::factory()->create();
    $user = User::factory()->create();

    $response = $this->actingAs($invitingUser)->postJson('/api/users/invite', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => $user->email,
    ]);

    $response->assertStatus(422);
});

test('a invited user can accept their invitation', function (): void {
    // inviting a new user should create both a user and an invitation
    $invitation = Invitation::factory()->create();

    Event::fake();

    $verificationUrl = $invitation->getSignedLink();

    $response = $this->get($verificationUrl);

    Event::assertDispatched(Verified::class);
    $user = User::find($invitation->user->id);
    $this->assertTrue($user->hasVerifiedEmail());
    $this->assertTrue($user->active);
    $this->assertTrue($user->new_password_required);

    $response->assertRedirect(config('app.frontend_url').'#/auth/login?verified=1'.'&email='.$user->email);
});

test('a user can see their sent invitations', function (): void {
    $user = User::factory()->create();

    $invitations = Invitation::factory()->count(5)->create([
        'invited_by' => $user->id,
    ]);

    $response = $this->actingAs($user)->getJson('/api/user/invitations');

    expect($response->json('data'))->toHaveCount(5);

    $response->assertOk();
});

test('a user cannot invite a user unless the domain is in the autorized list', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/users/invite', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'super@bad.com',
    ]);

    $response->assertStatus(422);

});
