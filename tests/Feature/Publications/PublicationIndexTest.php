<?php

use App\Enums\Permissions\UserRole;
use App\Models\Publication;
use App\Models\Region;
use App\Models\User;

test('authenticated user required to access publications index', function () {
    $response = $this->getJson('/api/publications');

    $response->assertUnauthorized();
});

test('regular user sees only published publications', function () {
    $user = User::factory()->create();

    $published = Publication::factory()->count(3)->published()->create();
    $accepted = Publication::factory()->count(2)->create();

    $response = $this->actingAs($user)->getJson('/api/publications');

    $response->assertOk();
    $response->assertJsonCount(3, 'data');

    $returnedStatuses = collect($response->json('data'))->pluck('data.status')->unique();
    expect($returnedStatuses->toArray())->toBe(['published']);
});

test('regional editor sees published publications and unpublished in their region', function () {
    $nflRegion = Region::whereSlug('nfl')->first();
    $marRegion = Region::whereSlug('mar')->first();

    $regionalEditor = User::factory()->create();
    $regionalEditor->assignRole(UserRole::NFL_EDITOR);

    // Published in NFL region - should see
    $publishedNfl = Publication::factory()->published()->create(['region_id' => $nflRegion->id]);

    // Unpublished in NFL region - should see
    $acceptedNfl = Publication::factory()->create(['region_id' => $nflRegion->id]);

    // Unpublished in MAR region - should NOT see
    $acceptedMar = Publication::factory()->create(['region_id' => $marRegion->id]);

    // Published in MAR region - should see
    $publishedMar = Publication::factory()->published()->create(['region_id' => $marRegion->id]);

    $response = $this->actingAs($regionalEditor)->getJson('/api/publications');

    $response->assertOk();
    $response->assertJsonCount(3, 'data');

    $returnedIds = collect($response->json('data'))->pluck('data.id')->toArray();
    expect($returnedIds)->toContain($publishedNfl->id, $acceptedNfl->id, $publishedMar->id);
    expect($returnedIds)->not->toContain($acceptedMar->id);
});

test('editor with UPDATE_PUBLICATIONS sees all publications regardless of status or region', function () {
    $nflRegion = Region::whereSlug('nfl')->first();
    $marRegion = Region::whereSlug('mar')->first();

    $editor = User::factory()->create();
    $editor->assignRole(UserRole::EDITOR);

    $publishedNfl = Publication::factory()->published()->create(['region_id' => $nflRegion->id]);
    $acceptedNfl = Publication::factory()->create(['region_id' => $nflRegion->id]);
    $publishedMar = Publication::factory()->published()->create(['region_id' => $marRegion->id]);
    $acceptedMar = Publication::factory()->create(['region_id' => $marRegion->id]);

    $response = $this->actingAs($editor)->getJson('/api/publications');

    $response->assertOk();
    $response->assertJsonCount(4, 'data');

    $returnedIds = collect($response->json('data'))->pluck('data.id')->toArray();
    expect($returnedIds)->toContain(
        $publishedNfl->id,
        $acceptedNfl->id,
        $publishedMar->id,
        $acceptedMar->id
    );
});

test('chief editor with UPDATE_PUBLICATIONS sees all publications', function () {
    $chiefEditor = User::factory()->create();
    $chiefEditor->assignRole(UserRole::CHIEF_EDITOR);

    $published = Publication::factory()->count(2)->published()->create();
    $accepted = Publication::factory()->count(2)->create();

    $response = $this->actingAs($chiefEditor)->getJson('/api/publications');

    $response->assertOk();
    $response->assertJsonCount(4, 'data');
});

test('multiple regional editor roles allow access to multiple regions', function () {
    $nflRegion = Region::whereSlug('nfl')->first();
    $marRegion = Region::whereSlug('mar')->first();
    $glfRegion = Region::whereSlug('glf')->first();

    $multiRegionalEditor = User::factory()->create();
    $multiRegionalEditor->assignRole([UserRole::NFL_EDITOR, UserRole::MAR_EDITOR]);

    // Should see unpublished in NFL and MAR, but not GLF
    $acceptedNfl = Publication::factory()->create(['region_id' => $nflRegion->id]);
    $acceptedMar = Publication::factory()->create(['region_id' => $marRegion->id]);
    $acceptedGlf = Publication::factory()->create(['region_id' => $glfRegion->id]);

    $response = $this->actingAs($multiRegionalEditor)->getJson('/api/publications');

    $response->assertOk();
    $response->assertJsonCount(2, 'data');

    $returnedIds = collect($response->json('data'))->pluck('data.id')->toArray();
    expect($returnedIds)->toContain($acceptedNfl->id, $acceptedMar->id);
    expect($returnedIds)->not->toContain($acceptedGlf->id);
});

test('supports pagination and filtering with regional scoping', function () {
    $nflRegion = Region::whereSlug('nfl')->first();

    $regionalEditor = User::factory()->create();
    $regionalEditor->assignRole(UserRole::NFL_EDITOR);

    Publication::factory()->count(5)->published()->create(['region_id' => $nflRegion->id]);
    Publication::factory()->count(3)->create(['region_id' => $nflRegion->id]);

    $response = $this->actingAs($regionalEditor)->getJson('/api/publications?limit=5');

    $response->assertOk();
    $response->assertJsonCount(5, 'data');
    expect($response->json('meta.total'))->toBe(8);
    expect($response->json('meta.per_page'))->toBe(5);
});

test('can filter publications by region_id', function () {
    $nflRegion = Region::whereSlug('nfl')->first();
    $marRegion = Region::whereSlug('mar')->first();

    $editor = User::factory()->create();
    $editor->assignRole(UserRole::EDITOR);

    $nflPublications = Publication::factory()->count(3)->published()->create(['region_id' => $nflRegion->id]);
    $marPublications = Publication::factory()->count(2)->published()->create(['region_id' => $marRegion->id]);

    $response = $this->actingAs($editor)->getJson("/api/publications?filter[region_id]={$nflRegion->id}");

    $response->assertOk();
    $response->assertJsonCount(3, 'data');

    $returnedIds = collect($response->json('data'))->pluck('data.id')->toArray();
    foreach ($nflPublications as $pub) {
        expect($returnedIds)->toContain($pub->id);
    }
    foreach ($marPublications as $pub) {
        expect($returnedIds)->not->toContain($pub->id);
    }
});
