<?php

use App\Models\Publication;
use App\Models\User;

/** Test that a user can query publications */
test('a user can get a list of publications', function () {
    $user = User::factory()->create();
    $publications = Publication::factory()->count(5)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->getJson('/api/publications');

    $response->assertOk();
    $response->assertJsonCount(5, 'data');
});

test('a user can get a list of publications with a filter', function () {
    $user = User::factory()->create();
    $publications = Publication::factory()->count(5)->create(['user_id' => $user->id]);
    $openAccessPublication = Publication::factory()->openAccess()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->getJson('/api/publications?filter[open_access]=1');

    $response->assertOk();
    $response->assertJsonCount(1, 'data');
    ray($response->json('data'));
    expect($response->json('data.0.data.id'))->toBe($openAccessPublication->id);
});

/** Test that a user can get a list of their publications */
test('a user can get a list of their publications', function () {
    $user = User::factory()->create();
    $publications = Publication::factory()->count(5)->create(['user_id' => $user->id]);
    Publication::factory()->count(5)->create();

    $response = $this->actingAs($user)->getJson('/api/my/publications');

    $response->assertOk();
    $response->assertJsonCount(5, 'data');
});

/** Test that a user can create a new publication */
