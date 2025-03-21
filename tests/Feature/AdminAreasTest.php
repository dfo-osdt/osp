<?php

use App\Models\User;

test('General health route is accessible', function () {
    $response = $this->get('/health');
    $response->assertOk();
});

test('An unauthenticated user cannot access the admin areas', function (string $path) {
    $response = $this->get($path);
    $response->assertForbidden();
})->with([
    '/horizon',
    '/pulse',
]);

test('An unauthenticated user cannot access the Librarium admin area', function () {
    $response = $this->get('/librarium');
    $response->assertRedirect('librarium/login');
});

test('An unauthorized user cannot access the admin areas', function (string $path) {
    // create regular user
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get($path);
    $response->assertForbidden();
})->with([
    '/horizon',
    '/pulse',
    '/librarium',
]);

test('An admin user can access the admin areas', function (string $path) {
    // create admin user
    $user = User::factory()->create();
    $user->assignRole('admin');
    $response = $this->actingAs($user)->get($path);
    $response->assertOk();
})->with([
    '/horizon',
    '/pulse',
    '/librarium',
]);
