<?php

use App\Events\Auth\PasswordChanged;
use App\Models\User;

test('a logged in user can change their password', function (): void {
    Event::fake();

    $user = User::factory()->create(['new_password_required' => true]);

    $password = Str::random(16);

    $response = $this->actingAs($user)->postJson('/change-password', [
        'current_password' => 'password',
        'password' => $password,
        'password_confirmation' => $password,
        'locale' => 'fr',
    ]);

    // check app locale is set to fr
    $this->assertEquals('fr', app()->getLocale());

    $response->assertOk();

    Event::assertDispatched(PasswordChanged::class, fn($event): bool => $event->user->id === $user->id);

    $user->refresh();
    $this->assertFalse($user->new_password_required);
});

test('a non authenticated cannot access the change-password route', function (): void {
    $user = User::factory()->create();

    $password = Str::random(16);

    $response = $this->postJson('/change-password', [
        'current_password' => 'password',
        'password' => $password,
        'password_confirmation' => $password,
    ]);

    $response->assertUnauthorized();
});
