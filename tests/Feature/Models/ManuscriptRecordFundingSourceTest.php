<?php

use App\Models\ManuscriptRecord;
use App\Models\User;

test('a user can list the funding source associated with a manuscript', function () {
    $unauthorizedUser = User::factory()->create();
    $authorizedUser = User::factory()->create();

    // a manuscript with funding sources
    $manuscript = ManuscriptRecord::factory()->hasFundingSources(3)->create(['user_id' => $authorizedUser->id]);

    $response = $this->actingAs($unauthorizedUser)->getJson('/api/manuscript-records/'.$manuscript->id.'/funding-sources')->assertForbidden();

    $response = $this->actingAs($authorizedUser)->getJson('/api/manuscript-records/'.$manuscript->id.'/funding-sources');

    $response->assertStatus(200);
    ray($response->json());
    expect($response->json('data'))->toHaveCount(3);
});

test('a user can add a new funding source to their manuscript', function () {
    $unauthorizedUser = User::factory()->create();
    $authorizedUser = User::factory()->create();

    // a manuscript with funding sources
    $manuscript = ManuscriptRecord::factory()->create(['user_id' => $authorizedUser->id]);

    $funder = \App\Models\Funder::factory()->create();

    $response = $this->actingAs($unauthorizedUser)->postJson('/api/manuscript-records/'.$manuscript->id.'/funding-sources', [
        'funder_id' => $funder->id,
        'title' => 'A new funding source',
        'description' => 'A description of the funding source',
    ])->assertForbidden();

    $response = $this->actingAs($authorizedUser)->postJson('/api/manuscript-records/'.$manuscript->id.'/funding-sources', [
        'funder_id' => $funder->id,
        'title' => 'A new funding source',
        'description' => 'A description of the funding source',
    ]);

    $response->assertStatus(201);
    ray($response->json());
});

test('a user can update an existing funding source on their manuscript', function () {
    $unauthorizedUser = User::factory()->create();
    $authorizedUser = User::factory()->create();

    // a manuscript with funding sources
    $manuscript = ManuscriptRecord::factory()->hasFundingSources(1)->create(['user_id' => $authorizedUser->id]);

    $funder = \App\Models\Funder::factory()->create();

    $response = $this->actingAs($unauthorizedUser)->putJson('/api/manuscript-records/'.$manuscript->id.'/funding-sources/'.$manuscript->fundingSources->first()->id, [
        'funder_id' => $funder->id,
        'title' => 'A new funding source',
        'description' => 'A description of the funding source',
    ])->assertForbidden();

    $response = $this->actingAs($authorizedUser)->putJson('/api/manuscript-records/'.$manuscript->id.'/funding-sources/'.$manuscript->fundingSources->first()->id, [
        'funder_id' => $funder->id,
        'title' => 'A new funding source',
        'description' => 'A description of the funding source',
    ]);

    $response->assertStatus(200);
    ray($response->json());
});

test('a user can delete a funding source on their manuscript', function () {
    $unauthorizedUser = User::factory()->create();
    $authorizedUser = User::factory()->create();

    // a manuscript with funding sources
    $manuscript = ManuscriptRecord::factory()->hasFundingSources(1)->create(['user_id' => $authorizedUser->id]);

    $response = $this->actingAs($unauthorizedUser)->deleteJson('/api/manuscript-records/'.$manuscript->id.'/funding-sources/'.$manuscript->fundingSources->first()->id)->assertForbidden();

    $response = $this->actingAs($authorizedUser)->deleteJson('/api/manuscript-records/'.$manuscript->id.'/funding-sources/'.$manuscript->fundingSources->first()->id);

    $response->assertNoContent();
});
