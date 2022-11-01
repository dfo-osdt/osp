<?php

use App\Models\Journal;
use App\Models\User;

test('a user can get a list of journals', function () {
    // create a user
    $user = User::factory()->create();

    // unauthenticated user cannot get a list of journals
    $response = $this->getJson('/api/journals')->assertUnauthorized();

    Journal::factory()->count(20)->create();

    // authenticated user can get a list of journals
    $response = $this->actingAs($user)->getJson('/api/journals')->assertOk();
    expect($response->json('data'))->toHaveCount(config('osp.api_pagination.default', 10));
    expect($response->json('meta.total'))->toBe(Journal::count());
});

test('a user can get a list of all dfo journals', function () {
    // create a user
    $user = User::factory()->create();

    // Seeder should have created the 6 DFO journals
    // Create a few more journals so confirm test only returns DFO journals
    Journal::factory()->count(5)->create();

    // authenticated user can get a list of journals
    $response = $this->actingAs($user)->getJson('/api/journals?filter[dfo_series]=1')->assertOk();

    expect($response->json('data'))->toHaveCount(6);
});
