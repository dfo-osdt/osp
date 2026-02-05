<?php

use App\Http\Resources\OrganizationResource;
use App\Models\Organization;

test('a user can get a list of all organization', function (): void {
    //

    Organization::factory()->count(20)->create();

    // unauthenticated user
    $response = $this->getJson('/api/organizations')->assertUnauthorized();

    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->getJson('/api/organizations?limit=15')->assertOk();

    expect($response->json('data'))->toHaveCount(15);
    expect($response->json('meta.total'))->toBe(\App\Models\Organization::query()->count());
});

test('a user can create a new organization', function (): void {
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
    expect(\App\Models\Organization::query()->find($response->json('data.id')))->toMatchArray($data)->toHaveKey('is_validated', false);
});

test('a user can create a new organization without the abbreviation', function (): void {
    $user = \App\Models\User::factory()->create();

    $data = [
        'name_en' => 'Test Organization',
        'name_fr' => 'Test Organization',
    ];

    $response = $this->actingAs($user)->postJson('/api/organizations', $data)->assertCreated()->assertJson([
        'data' => $data,
    ]);

    // make sure it exists in DB and that it is not validated.
    expect(\App\Models\Organization::query()->find($response->json('data.id')))->toMatchArray($data)->toHaveKey('is_validated', false);
});

test('a user cannot create the same organization twice', function (): void {
    $user = \App\Models\User::factory()->create();

    $data = [
        'name_en' => 'Test Organization',
        'name_fr' => 'Test Organization',
    ];

    $response = $this->actingAs($user)->postJson('/api/organizations', $data)->assertCreated()->assertJson([
        'data' => $data,
    ]);

    // make sure it exists in DB and that it is not validated.
    expect(\App\Models\Organization::query()->find($response->json('data.id')))->toMatchArray($data)->toHaveKey('is_validated', false);

    $response = $this->actingAs($user)->postJson('/api/organizations', $data)->assertStatus(422)->assertJsonValidationErrors(['name_en', 'name_fr']);
});

test('a user can see an organization', function (): void {
    $user = \App\Models\User::factory()->create();

    $organization = Organization::factory()->create();

    $response = $this->actingAs($user)->getJson('/api/organizations/'.$organization->id)->assertOk();

    $resource = OrganizationResource::make($organization)->resolve();

    expect($response->json())->toMatchArray($resource);
});

test('a user can filter organizations with nested filter parameters', function (): void {
    $user = \App\Models\User::factory()->create();

    Organization::factory()->count(10)->create();

    $response = $this->actingAs($user)
        ->getJson('/api/organizations?filter[search]=town of chatam, massachusetts&limit=10&page=1&sort=name-en-length,name_en')
        ->assertOk();

    expect($response->json())->toHaveKey('data');
});

test('a user can filter organizations with comma-separated search terms', function (): void {
    $user = \App\Models\User::factory()->create();

    $org1 = Organization::factory()->create([
        'name_en' => 'XYZ Toronto ABC',
        'name_fr' => 'XYZ Toronto ABC',
    ]);
    $org2 = Organization::factory()->create([
        'name_en' => 'XYZ Massachusetts ABC',
        'name_fr' => 'XYZ Massachusetts ABC',
    ]);
    $org3 = Organization::factory()->create([
        'name_en' => 'XYZ Other ABC',
        'name_fr' => 'XYZ Autre ABC',
    ]);

    $response = $this->actingAs($user)
        ->getJson('/api/organizations?filter[search]=Toronto,Massachusetts')
        ->assertOk();

    $data = $response->json('data');
    $returnedIds = collect($data)->pluck('data.id')->all();

    // Should find organizations matching either term
    expect($returnedIds)->toContain($org1->id)
        ->and($returnedIds)->toContain($org2->id)
        ->and($returnedIds)->not->toContain($org3->id);
});

test('filtering with empty search value returns all results', function (): void {
    $user = \App\Models\User::factory()->create();

    // Get current count
    $totalCount = Organization::query()->count();

    $response = $this->actingAs($user)
        ->getJson('/api/organizations?filter[search]=')
        ->assertOk();

    // Empty filter should return all organizations (respecting pagination)
    expect($response->json('meta.total'))->toBe($totalCount);
});
