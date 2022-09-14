<?php

use App\Models\Organization;

test('a user can get a list of all organization', function () {
    //$this->seed();

    Organization::factory()->count(20)->create();

    // unauthenticated user
    $response = $this->getJson('/api/organizations')->assertUnauthorized();

    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->getJson('/api/organizations?limit=15')->assertOk();

    expect($response->json('data'))->toHaveCount(15);
    expect($response->json('meta.total'))->toBe(20);
});
