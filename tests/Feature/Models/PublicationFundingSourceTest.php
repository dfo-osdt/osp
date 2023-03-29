<?php

use App\Models\Publication;
use App\Models\User;

test('a user can list the funding source associated with a publication', function () {
    $unauthorizedUser = User::factory()->create();
    $authorizedUser = User::factory()->create();

    // a publication with funding sources
    $publication = Publication::factory()->hasFundingSources(3)->create(['user_id' => $authorizedUser->id]);

    $response = $this->actingAs($unauthorizedUser)->getJson('/api/publications/'.$publication->id.'/funding-sources')->assertForbidden();

    $response = $this->actingAs($authorizedUser)->getJson('/api/publications/'.$publication->id.'/funding-sources');

    $response->assertStatus(200);
    ray($response->json());
    expect($response->json('data'))->toHaveCount(3);
});

test('a user can add a new funding source to their publication', function () {
    $unauthorizedUser = User::factory()->create();
    $authorizedUser = User::factory()->create();

    // a publication with funding sources
    $publication = Publication::factory()->create(['user_id' => $authorizedUser->id]);

    $funder = \App\Models\Funder::factory()->create();

    $response = $this->actingAs($unauthorizedUser)->postJson('/api/publications/'.$publication->id.'/funding-sources', [
        'funder_id' => $funder->id,
        'title' => 'A new funding source',
        'description' => 'A description of the funding source',
    ])->assertForbidden();

    $response = $this->actingAs($authorizedUser)->postJson('/api/publications/'.$publication->id.'/funding-sources', [
        'funder_id' => $funder->id,
        'title' => 'A new funding source',
        'description' => 'A description of the funding source',
    ]);

    $response->assertStatus(201);
    ray($response->json());
});

test('a user can update an existing funding source on their publication', function () {
    $unauthorizedUser = User::factory()->create();
    $authorizedUser = User::factory()->create();

    // a publication with funding sources
    $publication = Publication::factory()->hasFundingSources(1)->create(['user_id' => $authorizedUser->id]);

    $funder = \App\Models\Funder::factory()->create();

    $response = $this->actingAs($unauthorizedUser)->putJson('/api/publications/'.$publication->id.'/funding-sources/'.$publication->fundingSources->first()->id, [
        'funder_id' => $funder->id,
        'title' => 'A new funding source',
        'description' => 'A description of the funding source',
    ])->assertForbidden();

    $response = $this->actingAs($authorizedUser)->putJson('/api/publications/'.$publication->id.'/funding-sources/'.$publication->fundingSources->first()->id, [
        'funder_id' => $funder->id,
        'title' => 'A new funding source',
        'description' => 'A description of the funding source',
    ]);

    $response->assertStatus(200);
    ray($response->json());
});

test('a user can delete a funding source on their publication', function () {
    $unauthorizedUser = User::factory()->create();
    $authorizedUser = User::factory()->create();

    // a publication with funding sources
    $publication = Publication::factory()->hasFundingSources(1)->create(['user_id' => $authorizedUser->id]);

    $response = $this->actingAs($unauthorizedUser)->deleteJson('/api/publications/'.$publication->id.'/funding-sources/'.$publication->fundingSources->first()->id)->assertForbidden();

    $response = $this->actingAs($authorizedUser)->deleteJson('/api/publications/'.$publication->id.'/funding-sources/'.$publication->fundingSources->first()->id);

    $response->assertNoContent();
});
