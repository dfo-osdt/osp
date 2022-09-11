<?php

use App\Enums\ManuscriptRecordStatus;
use App\Enums\ManuscriptRecordType;
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

    $this->postJson('/api/manuscripts', $submit_data)->assertUnauthorized();

    $response = $this->actingAs($user)->postJson('/api/manuscripts', $submit_data)->assertCreated();

    ray($response->json());
    expect($response->json('data'))->toMatchArray($manuscript_data->toArray());
    expect(ManuscriptRecord::find($response->json('data.id')))->toMatchArray($manuscript_data->toArray());
});

test('an authenticated users can get a list of their manuscripts', function () {
    $this->seed();

    $manuscripts = ManuscriptRecord::factory()->count(5)->create();
    $user = User::factory()->create();
    $manuscripts = ManuscriptRecord::factory()->count(5)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->getJson('/api/my/manuscripts')->assertOk();

    expect($response->json('data'))->toHaveCount(5);
});
