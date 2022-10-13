<?php

use App\Models\User;

test('an authenticated user can search through the users', function () {
    User::factory()->count(20)->create();

    $user = User::find(1);

    $this->getJSON('api/users?limit=10')->assertUnauthorized();

    $response = $this->actingAs($user)->getJSON('/api/users?limit=10')->assertOk()->assertJsonCount(10, 'data');
    expect($response->json('meta.total'))->toBe(User::count());
});

test('a user can view their profile and profile has author relationship', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->getJson('api/users/'.$user->id)->assertOk();

    expect($response->json('data'))->toHaveKeys(['id', 'first_name', 'last_name', 'email', 'author']);
});

test('a user can update their profile', function () {
    $user = User::factory()->create();
    $user2 = User::factory()->create();

    $data = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@dfo-mpo.gc.ca',
    ];

    $this->actingAs($user2)->putJson('api/users/'.$user->id, $data)->assertForbidden();
    $response = $this->actingAs($user)->putJson('api/users/'.$user->id, $data)->assertOk();
    expect($response->json('data'))->toMatchArray(collect($data)->only('first_name', 'last_name')->toArray());
    expect($response->json('data.email'))->toBe($user->email);

    expect($response->json('data.author.data'))->toMatchArray(collect($data)->only('first_name', 'last_name')->toArray());
});
