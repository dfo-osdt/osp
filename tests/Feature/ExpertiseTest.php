<?php

use App\Models\Expertise;
use App\Models\User;

test('a user can get a list of expertise', function () {

    Expertise::factory()->count(4)->create();
    $expertise = Expertise::factory()->create(
        [
            'name_en' => 'English',
            'name_fr' => 'French',
        ]
    );

    $response = $this->getJson('/api/expertises');
    $response->assertUnauthorized();

    $user = User::factory()->create();
    $response = $this->actingAs($user)->getJson('/api/expertises');

    $response->assertOk();

    // search
    $response = $this->actingAs($user)->getJson('/api/expertises?filter[search]=english');
    $response->assertOk();
    ray($response->json('data'));
});

test('a use can get a single expertise', function () {

    Expertise::factory()->count(4)->create();
    $expertise = Expertise::factory()->create(
        [
            'name_en' => 'English',
            'name_fr' => 'French',
        ]
    );

    $response = $this->getJson('/api/expertises/'.$expertise->id);
    $response->assertUnauthorized();

    $user = User::factory()->create();
    $response = $this->actingAs($user)->getJson('/api/expertises/'.$expertise->id);

    $response->assertOk();
});
