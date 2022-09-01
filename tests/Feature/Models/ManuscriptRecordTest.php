<?php

use App\Enums\ManuscriptRecordStatus;
use App\Enums\ManuscriptRecordType;
use App\Models\User;

test('an authenticated user can create a new manuscript', function () {
    $this->seed();

    $user = User::factory()->create();

    // expected data
    $manuscript_data = collect([
        'type' => ManuscriptRecordType::PRIMARY->value,
        'status' => ManuscriptRecordStatus::DRAFT->value,
        'title' => "My manuscript working title",
        'region_id' => rand(1,8),
        'user_id' => $user->id,
    ]);

    $submit_data = $manuscript_data->only(['title', 'region_id', 'type'])->toArray();

    $this->postJson('/api/manuscripts', $submit_data)->assertUnauthorized();

    $response = $this->actingAs($user)->postJson('/api/manuscripts', $submit_data)->assertCreated();

    expect($response->json())->toMatchArray($manuscript_data->toArray());

});
