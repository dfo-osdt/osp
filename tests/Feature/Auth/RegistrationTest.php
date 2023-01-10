<?php

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
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'test@example.com',
        'password' => $password,
        'password_confirmation' => $password,
    ]);
    $response->assertJson([
        'message' => 'registered',
        'email' => 'test@example.com',
    ]);
});

test('an email is always stored in lowercase', function () {
    $email = 'John.Doe@jel.com';

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
    expect($user->email)->toBe('john.doe@jel.com');
    // check that author profile is created
    expect($user->author->email)->toBe('john.doe@jel.com');
    // check that user is assigned author role
    expect($user->hasRole('author'))->toBeTrue();
});

test('a user cannot register twice with the same email', function () {
    $email = 'John.Doe@jel.com';

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

    $response = $this->postJson('/register', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => $email,
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    expect($response->status())->toBe(422);
    expect($response->json('errors.email.0'))->toBe('The email has already been taken.');
});
