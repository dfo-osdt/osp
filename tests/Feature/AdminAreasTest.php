<?php

use App\Models\User;

test('An unauthenticated user cannot access the admin areas', function (string $path) {
    $response = $this->get($path);
    $response->assertForbidden();
})->with(['/horizon']);

test('An unauthorized user cannot access the admin areas', function (string $path) {
    // create regular user
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get($path);
    $response->assertForbidden();
})->with(['/horizon']);

test('An admin user can access the admin areas', function (string $path) {
    // create admin user
    $user = User::factory()->create();
    $user->assignRole('admin');
    $response = $this->actingAs($user)->get($path);
    $response->assertOk();
})->with(['/horizon']);
