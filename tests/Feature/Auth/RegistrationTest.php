<?php

use App\Models\Invitation;
use App\Models\User;

test('new users cannot register with poor password', function () {
    $response = $this->post('/register', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'test@example.com',
        'password' => 'passwordpassword',
        'password_confirmation' => 'passwordpassword',
    ]);

    $response->assertStatus(422);
});

test('new users can register', function () {
    // generate random 16 character long string
    $password = Str::random(12);

    $response = $this->post('/register', [
        'first_name' => 'John-Super',
        'last_name' => 'Doe-Andreé',
        'email' => 'test@example.com',
        'password' => $password,
        'password_confirmation' => $password,
    ]);
    $response->assertJson([
        'message' => 'registered',
        'email' => 'test@example.com',
    ]);
});

test('new user cannot logging until they have verified their email address', function () {

    $user = User::factory()->unverified()->create([
        'email_verified_at' => null,
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertStatus(422);
    $response->assertJson([
        'message' => 'Your email address has not been verified.',
    ]);
});

test('an email is always stored in lowercase', function () {
    $email = 'John.Doe@example.com';

    // generate random 16 character long string
    $password = Str::random(12);

    $response = $this->post('/register', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => $email,
        'password' => $password,
        'password_confirmation' => $password,
    ]);

    $user = User::latest()->first();
    expect($user->email)->toBe('john.doe@example.com');
    // check that author profile is created
    expect($user->author->email)->toBe('john.doe@example.com');
    // check that user is assigned author role
    expect($user->hasRole('author'))->toBeTrue();
});

test('a user cannot register twice with the same email', function () {
    $email = 'John.Doe@example.com';

    // generate random 16 character long string
    $password = Str::random(12);

    $response = $this->postJson('/register', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => $email,
        'password' => $password,
        'password_confirmation' => $password,
    ]);

    $response->assertOk();

    // try to register again with the same email before verification, should work.
    // scenario: user registers, but does not verify email and forgets..., then tries to register again.

    $response = $this->postJson('/register', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => $email,
        'password' => $password,
        'password_confirmation' => $password,
    ]);

    $response->assertOk();

    // try to register again with the same email after verification, should fail.
    $user = User::latest()->first();
    $user->email_verification_token = null;
    $user->email_verified_at = now();
    $user->active = true;
    $user->save();

    $response = $this->postJson('/register', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => $email,
        'password' => $password,
        'password_confirmation' => $password,
    ]);

    expect($response->status())->toBe(422);
    expect($response->json('message'))->toBe('Problem with registration, please contact support');
});

test('a user that was invited can register without following the link', function () {
    $invitation = Invitation::factory()->create();
    $invitedUser = $invitation->user;

    // generate random 16 character long string
    $password = Str::random(12);

    $response = $this->postJson('/register', [
        'first_name' => $invitedUser->first_name,
        'last_name' => $invitedUser->last_name,
        'email' => $invitedUser->email,
        'password' => $password,
        'password_confirmation' => $password,
    ]);

    $response->assertOk();
});

test('a user cannot register with the wrong domain', function () {

    $password = Str::random(12);

    $response = $this->postJson('/register', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@wrong.com',
        'password' => $password,
        'password_confirmation' => $password,
    ]);

    expect($response->status())
        ->toBe(422)
        ->and($response->json('message'))
        ->toBe('Email domain not allowed');
});
