<?php

use App\Models\ManuscriptRecord;
use App\Models\User;

test('a user can list the published preprints', function () {

    $user = User::factory()->create();

    // need to be logged in
    $response = $this->getJson('/api/preprints');
    $response->assertUnauthorized();

    ManuscriptRecord::factory()->publishedPreprint()->count(5)->create();

    $response = $this->actingAs($user)->getJson('/api/preprints');
    $response->assertStatus(200);
    expect($response->json('data'))->toHaveCount(5);

    //limit to 2..
    $response = $this->actingAs($user)->getJson('/api/preprints?limit=2');
    $response->assertStatus(200);
    expect($response->json('data'))->toHaveCount(2);

    dd($response->json('data'));
});
