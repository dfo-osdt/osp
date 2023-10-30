<?php

use App\Models\Author;
use App\Models\Expertise;
use App\Models\User;

test('we can get a list of an authors expertise', function () {

    $author = Author::factory()->hasExpertises(5)->create();

    $user = User::factory()->create();

    // can only get when logged in
    $response = $this->getJson('/api/authors/' . $author->id . '/expertises');
    $response->assertUnauthorized();

    // get the expertise for this author
    $response = $this->actingAs($user)->getJson('/api/authors/' . $author->id . '/expertises');
    $response->assertOk();
    expect($response->json('data'))->toHaveCount(5);
});

test('we can sync an authors expertise', function () {

    $expertise = Expertise::factory()->count(10)->create();

    $author = Author::factory()->hasExpertises(5)->create();

    $user = User::factory()->create();

    // can only get when logged in
    $response = $this->postJson('/api/authors/' . $author->id . '/expertises');
    $response->assertUnauthorized();

    $sync = $expertise->random(3)->sortBy('name_en')->pluck('id')->toArray();
    ray($sync);

    // get the expertise for this author
    $response = $this->actingAs($user)->postJson('/api/authors/' . $author->id . '/expertises', [
        'expertises' => $sync,
    ]);
    $response->assertOk();
    expect($response->json('data'))->toHaveCount(3);
    ray($response->json('data.*.data.id'));
    expect($response->json('data.*.data.id'))->toBe($sync);


    // make sure sending empty POST request doesn't erase all expertise
    $response = $this->actingAs($user)->postJson('/api/authors/' . $author->id . '/expertises', []);
    $response->assertInvalid(['expertises']);

    // remove all expertise
    $response = $this->actingAs($user)->postJson('/api/authors/' . $author->id . '/expertises', [
        'expertises' => [],
    ]);
    $response->assertOk();
    expect($response->json('data'))->toHaveCount(0);
});
