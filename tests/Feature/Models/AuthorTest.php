<?php

use App\Models\Author;
use App\Models\User;

test('a user can get list of authors', function () {
    $this->seed();

    Author::factory()->count(10)->create();

    $user = User::factory()->create();

    // only when user is logged in
    $response = $this->getJson('api/authors')->assertUnauthorized();

    $response = $this->actingAs($user)->getJson('api/authors');

    $response->assertOk()->assertJsonCount(10, 'data');
});
