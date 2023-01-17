<?php

use App\Events\Auth\Invited;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Auth\Events\Verified;

test('an existing user can invite a new user', function () {
    // inviting a new user should create both a user and an invitation
    Event::fake();

    $invitingUser = User::factory()->create();

    $response = $this->actingAs($invitingUser)->postJson('/api/users/invite', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@test.com',
        'locale' => 'fr',
    ]);

    $response->assertCreated();
    $response->assertJson([
        'data' => [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@test.com',
            'locale' => 'fr',
        ]]);

    Event::assertDispatched(Invited::class, function ($event) {
        ray($event);
        if ($event->password === null) {
            return false;
        }

        return $event->invitation->user->email === 'john.doe@test.com';
    });
});

test('a user cannot invited a user that already exists', function () {
    // inviting a new user should create both a user and an invitation
    Event::fake();

    $invitingUser = User::factory()->create();
    $user = User::factory()->create();

    $response = $this->actingAs($invitingUser)->postJson('/api/users/invite', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => $user->email,
    ]);

    $response->assertStatus(422);
    ray($response->json());
});

test('a invited user can accept their invitation', function () {
    // inviting a new user should create both a user and an invitation
    $invitation = Invitation::factory()->create();

    Event::fake();

    $verificationUrl = $invitation->getSignedLink();

    ray($verificationUrl);

    $response = $this->get($verificationUrl);

    ray($response);

    Event::assertDispatched(Verified::class);
    $user = User::find($invitation->user->id);
    $this->assertTrue($user->hasVerifiedEmail());
    $this->assertTrue($user->active);
    $this->assertTrue($user->new_password_required);

    $response->assertRedirect(config('app.frontend_url').'#/auth/login?verified=1'.'&email='.$user->email);
});
