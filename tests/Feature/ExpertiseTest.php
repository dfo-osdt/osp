<?php

use App\Models\Expertise;
use App\Models\User;

test('a user can get a list of expertise', function () {

    Expertise::factory()->withChildren(5)->count(4)->create();
    $expertise = Expertise::factory()->create(
        [
            'name_en' => 'english',
            'name_fr' => 'french',
        ]
    );

    $response = $this->getJson('/api/expertises');
    $response->assertUnauthorized();

    $user = User::factory()->create();
    $response = $this->actingAs($user)->getJson('/api/expertises');

    $response->assertOk();

    // include ancestors
    $response = $this->actingAs($user)->getJson('/api/expertises?include=ancestors');
    $response->assertOk();

    // search
    $response = $this->actingAs($user)->getJson('/api/expertises?filter[search]=english');
    $response->assertOk();
    ray($response->json('data'));
});
