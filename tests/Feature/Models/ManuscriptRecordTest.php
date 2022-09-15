<?php

use App\Enums\ManuscriptRecordStatus;
use App\Enums\ManuscriptRecordType;
use App\Models\ManuscriptAuthor;
use App\Models\ManuscriptRecord;
use App\Models\User;

test('an authenticated user can create a new manuscript', function () {
    $this->seed();

    $user = User::factory()->create();

    // expected data once created
    $manuscript_data = collect([
        'type' => ManuscriptRecordType::PRIMARY->value,
        'status' => ManuscriptRecordStatus::DRAFT->value,
        'title' => 'My manuscript working title',
        'region_id' => rand(1, 8),
        'user_id' => $user->id,
    ]);

    // data to be sent to the api
    $submit_data = $manuscript_data->only(['title', 'region_id', 'type'])->toArray();

    $this->postJson('/api/manuscript-records', $submit_data)->assertUnauthorized();

    $response = $this->actingAs($user)->postJson('/api/manuscript-records', $submit_data)->assertCreated();

    ray($response->json());
    expect($response->json('data'))->toMatchArray($manuscript_data->toArray());
    expect(ManuscriptRecord::find($response->json('data.id')))->toMatchArray($manuscript_data->toArray());
});

test('an authenticated users can get a list of their manuscripts', function () {
    $this->seed();

    $manuscripts = ManuscriptRecord::factory()->count(5)->create();
    $user = User::factory()->create();
    $manuscripts = ManuscriptRecord::factory()->has(ManuscriptAuthor::factory()->count(2))->count(5)->count(5)->create(['user_id' => $user->id]);

    ray()->showQueries();

    $response = $this->actingAs($user)->getJson('/api/my/manuscript-records')->assertOk();

    ray($response->json());

    expect($response->json('data'))->toHaveCount(5);
    // expect manuscript author to be included
    expect($response->json('data.0.data.manuscript_authors'))->toHaveCount(2);
});

test('an user can save a draft manuscript', function () {
    $this->seed();

    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->putJson("/api/manuscript-records/{$manuscript->id}", [
        'title' => 'My new title',
    ])->assertOk();

    expect($response->json('data.title'))->toBe('My new title');

    // check that the database has been updated
    expect(ManuscriptRecord::find($manuscript->id)->title)->toBe('My new title');

    $data = [
        'title' => 'My new title',
        'region_id' => 1,
        'type' => ManuscriptRecordType::SECONDARY->value,
        'abstract' => 'My new abstract',
        'pls_en' => 'My new pls_en',
        'pls_fr' => 'My new pls_fr',
        'scientific_implications' => 'My new scientific_implications',
        'regions_and_species' => 'My new regions_and_species',
        'relevant_to' => 'My new relevant_to',
        'additional_information' => 'My new additional_information',
    ];

    // update all the fields
    $response = $this->actingAs($user)->putJson("/api/manuscript-records/{$manuscript->id}", $data)->assertOk();

    // assert new data in response and database
    expect($response->json('data'))->toMatchArray($data);
    expect(ManuscriptRecord::find($manuscript->id))->toMatchArray($data);
});
