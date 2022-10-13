<?php

use App\Http\Resources\OrganizationResource;
use App\Models\Organization;

test('a user can get a list of all organization', function () {
    //

    Organization::factory()->count(20)->create();

    // unauthenticated user
    $response = $this->getJson('/api/organizations')->assertUnauthorized();

    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->getJson('/api/organizations?limit=15')->assertOk();

    expect($response->json('data'))->toHaveCount(15);
    expect($response->json('meta.total'))->toBe(Organization::count());
});

test('a user can create a new organization', function () {
    $user = \App\Models\User::factory()->create();

    $data = [
        'name_en' => 'Test Organization',
        'name_fr' => 'Test Organizations',
        'abbr_en' => 'TO',
        'abbr_fr' => 'TOS',
    ];

    $response = $this->actingAs($user)->postJson('/api/organizations', $data)->assertCreated()->assertJson([
        'data' => $data,
    ]);

    // make sure it exists in DB and that it is not validated.
    expect(Organization::find($response->json('data.id')))->toMatchArray($data)->toHaveKey('is_validated', false);
});

test('a user can create a new organization without the abbreviation', function () {
    $user = \App\Models\User::factory()->create();

    $data = [
        'name_en' => 'Test Organization',
        'name_fr' => 'Test Organization',
    ];

    $response = $this->actingAs($user)->postJson('/api/organizations', $data)->assertCreated()->assertJson([
        'data' => $data,
    ]);

    // make sure it exists in DB and that it is not validated.
    expect(Organization::find($response->json('data.id')))->toMatchArray($data)->toHaveKey('is_validated', false);
});

test('a user cannot create the same organization twice', function () {
    $user = \App\Models\User::factory()->create();

    $data = [
        'name_en' => 'Test Organization',
        'name_fr' => 'Test Organization',
    ];

    $response = $this->actingAs($user)->postJson('/api/organizations', $data)->assertCreated()->assertJson([
        'data' => $data,
    ]);

    // make sure it exists in DB and that it is not validated.
    expect(Organization::find($response->json('data.id')))->toMatchArray($data)->toHaveKey('is_validated', false);

    $response = $this->actingAs($user)->postJson('/api/organizations', $data)->assertStatus(422)->assertJsonValidationErrors(['name_en', 'name_fr']);
});

test('a user can see an organization', function () {
    $user = \App\Models\User::factory()->create();

    $organization = Organization::factory()->create();

    $response = $this->actingAs($user)->getJson('/api/organizations/'.$organization->id)->assertOk();

    $resource = OrganizationResource::make($organization)->resolve();

    expect($response->json())->toMatchArray($resource);
});
