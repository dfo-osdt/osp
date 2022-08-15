<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;


test('new users can register', function(){
    $response = $this->post('/register', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);
    $response->assertNoContent();

});


test('an email is always stored in lowercase',function(){

    $email = 'John.Doe@jel.com';

    $response = $this->post('/register', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => $email,
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $user = User::latest()->first();
    expect($user->email)->toBe('john.doe@jel.com');

});

test('a user cannot register twice with same email',function(){
    
    $email = 'John.Doe@jel.com';

    $response = $this->postJson('/register', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => $email,
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertNoContent();

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