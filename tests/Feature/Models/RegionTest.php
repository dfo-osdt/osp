<?php

use App\Models\Region;
use App\Models\User;

test('authenticated user can get regions', function () {
    $regions = Region::all();

    expect($regions)->toHaveCount(8);

    $response = $this->getJson('/api/regions');

    $response->assertUnauthorized();

    $user = User::factory()->create();

    $response = $this->actingAs($user)->getJson('/api/regions')->assertStatus(200);
    $response->assertJsonCount(8, 'data');
});
