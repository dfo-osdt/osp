<?php

use App\Enums\Permissions\UserRole;
use App\Enums\PublicationStatus;
use App\Models\Publication;
use App\Models\User;

test('regional observer sees published publications and unpublished in their region', function (): void {
    $nflObserver = User::factory()->create();
    $nflObserver->assignRole(UserRole::NFL_OBSERVER->value);

    // Create publications
    $publishedNfl = Publication::factory()->create([
        'status' => PublicationStatus::PUBLISHED,
        'region_id' => 1, // NFL region
    ]);

    $acceptedNfl = Publication::factory()->create([
        'status' => PublicationStatus::ACCEPTED,
        'region_id' => 1, // NFL region
    ]);

    $acceptedMar = Publication::factory()->create([
        'status' => PublicationStatus::ACCEPTED,
        'region_id' => 2, // MAR region (different)
    ]);

    $publishedMar = Publication::factory()->create([
        'status' => PublicationStatus::PUBLISHED,
        'region_id' => 2, // MAR region
    ]);

    $publications = Publication::query()->forUser($nflObserver)->get();
    $publicationIds = $publications->pluck('id')->all();

    // Observer should see:
    // - Published from their region
    // - Accepted from their region
    // - Published from other regions
    // Observer should NOT see:
    // - Accepted from other regions
    expect($publicationIds)->toContain($publishedNfl->id)
        ->and($publicationIds)->toContain($acceptedNfl->id)
        ->and($publicationIds)->toContain($publishedMar->id)
        ->and($publicationIds)->not->toContain($acceptedMar->id);
});

test('regional observer cannot update publications via policy', function (): void {
    $marObserver = User::factory()->create();
    $marObserver->assignRole(UserRole::MAR_OBSERVER->value);

    $publication = Publication::factory()->create([
        'status' => PublicationStatus::ACCEPTED,
        'region_id' => 2, // MAR region
    ]);

    // Observer should not have update permission
    expect($marObserver->can('update', $publication))->toBeFalse();
});

test('regional observer can view publications in their region', function (): void {
    $queObserver = User::factory()->create();
    $queObserver->assignRole(UserRole::QUE_OBSERVER->value);

    $publication = Publication::factory()->create([
        'status' => PublicationStatus::ACCEPTED,
        'region_id' => 4, // QUE region
    ]);

    $response = $this->actingAs($queObserver)
        ->getJson("/api/publications/{$publication->id}")
        ->assertOk();

    expect($response->json('data.id'))->toBe($publication->id);
});

test('regional observer sees unpublished publications from their region via scope', function (): void {
    $onpObserver = User::factory()->create();
    $onpObserver->assignRole(UserRole::ONP_OBSERVER->value);

    $onpRegion = \App\Models\Region::query()->where('slug', 'onp')->firstOrFail();
    $arcRegion = \App\Models\Region::query()->where('slug', 'arc')->firstOrFail();

    $acceptedOnp = Publication::factory()->create([
        'status' => PublicationStatus::ACCEPTED,
        'region_id' => $onpRegion->id,
    ]);

    $publishedOnp = Publication::factory()->create([
        'status' => PublicationStatus::PUBLISHED,
        'region_id' => $onpRegion->id,
    ]);

    $acceptedArc = Publication::factory()->create([
        'status' => PublicationStatus::ACCEPTED,
        'region_id' => $arcRegion->id,
    ]);

    $publications = Publication::query()->forUser($onpObserver)->get();
    $publicationIds = $publications->pluck('id')->all();

    expect($publicationIds)->toContain($acceptedOnp->id)
        ->and($publicationIds)->toContain($publishedOnp->id)
        ->and($publicationIds)->not->toContain($acceptedArc->id);
});

test('user with both observer and editor roles has editor permissions', function (): void {
    $user = User::factory()->create();
    $user->assignRole([UserRole::NFL_OBSERVER->value, UserRole::MAR_EDITOR->value]);

    $marPublication = Publication::factory()->create([
        'status' => PublicationStatus::ACCEPTED,
        'region_id' => 2, // MAR region
    ]);

    // Should be able to update MAR publication (editor permission)
    expect($user->can('update', $marPublication))->toBeTrue();
});

test('multiple regional observer roles allow viewing from multiple regions via scope', function (): void {
    $multiObserver = User::factory()->create();
    $multiObserver->assignRole([UserRole::ARC_OBSERVER->value, UserRole::PAC_OBSERVER->value]);

    $arcRegion = \App\Models\Region::query()->where('slug', 'arc')->firstOrFail();
    $pacRegion = \App\Models\Region::query()->where('slug', 'pac')->firstOrFail();
    $ncrRegion = \App\Models\Region::query()->where('slug', 'ncr')->firstOrFail();

    $arcPub = Publication::factory()->create([
        'status' => PublicationStatus::ACCEPTED,
        'region_id' => $arcRegion->id,
    ]);

    $pacPub = Publication::factory()->create([
        'status' => PublicationStatus::ACCEPTED,
        'region_id' => $pacRegion->id,
    ]);

    $ncrPub = Publication::factory()->create([
        'status' => PublicationStatus::ACCEPTED,
        'region_id' => $ncrRegion->id,
    ]);

    $publications = Publication::query()->forUser($multiObserver)->get();
    $publicationIds = $publications->pluck('id')->all();

    expect($publicationIds)->toContain($arcPub->id)
        ->and($publicationIds)->toContain($pacPub->id)
        ->and($publicationIds)->not->toContain($ncrPub->id);
});

test('regional observer sees all published publications regardless of region', function (): void {
    $glfObserver = User::factory()->create();
    $glfObserver->assignRole(UserRole::GLF_OBSERVER->value);

    $publishedGlf = Publication::factory()->create([
        'status' => PublicationStatus::PUBLISHED,
        'region_id' => 3, // GLF region
    ]);

    $publishedNfl = Publication::factory()->create([
        'status' => PublicationStatus::PUBLISHED,
        'region_id' => 1, // NFL region (different)
    ]);

    $publications = Publication::query()->forUser($glfObserver)->get();
    $publicationIds = $publications->pluck('id')->all();

    // All published publications should be visible
    expect($publicationIds)->toContain($publishedGlf->id)
        ->and($publicationIds)->toContain($publishedNfl->id);
});
