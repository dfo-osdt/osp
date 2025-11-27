<?php

use App\Enums\Permissions\UserRole;
use App\Models\Publication;
use App\Models\Region;
use App\Models\User;

test('regional editor can update publications in their region', function (): void {
    $nflRegion = Region::whereSlug('nfl')->first();

    $regionalEditor = User::factory()->create();
    $regionalEditor->assignRole(UserRole::NFL_EDITOR);

    $publication = Publication::factory()->create(['region_id' => $nflRegion->id]);

    expect($regionalEditor->can('update', $publication))->toBeTrue();
});

test('regional editor cannot update publications in other regions', function (): void {
    $nflRegion = Region::whereSlug('nfl')->first();
    $marRegion = Region::whereSlug('mar')->first();

    $nflEditor = User::factory()->create();
    $nflEditor->assignRole(UserRole::NFL_EDITOR);

    $marPublication = Publication::factory()->create(['region_id' => $marRegion->id]);

    expect($nflEditor->can('update', $marPublication))->toBeFalse();
});

test('regional editor can update both published and unpublished publications in their region', function (): void {
    $nflRegion = Region::whereSlug('nfl')->first();

    $regionalEditor = User::factory()->create();
    $regionalEditor->assignRole(UserRole::NFL_EDITOR);

    $publishedPub = Publication::factory()->published()->create(['region_id' => $nflRegion->id]);
    $acceptedPub = Publication::factory()->create(['region_id' => $nflRegion->id]);

    expect($regionalEditor->can('update', $publishedPub))->toBeTrue();
    expect($regionalEditor->can('update', $acceptedPub))->toBeTrue();
});

test('editor with UPDATE_PUBLICATIONS can update publications in any region', function (): void {
    $nflRegion = Region::whereSlug('nfl')->first();
    $marRegion = Region::whereSlug('mar')->first();

    $editor = User::factory()->create();
    $editor->assignRole(UserRole::EDITOR);

    $nflPub = Publication::factory()->create(['region_id' => $nflRegion->id]);
    $marPub = Publication::factory()->create(['region_id' => $marRegion->id]);

    expect($editor->can('update', $nflPub))->toBeTrue();
    expect($editor->can('update', $marPub))->toBeTrue();
});

test('publication owner can update their own publication regardless of region', function (): void {
    $nflRegion = Region::whereSlug('nfl')->first();

    $owner = User::factory()->create();

    $publication = Publication::factory()->create([
        'region_id' => $nflRegion->id,
        'user_id' => $owner->id,
    ]);

    expect($owner->can('update', $publication))->toBeTrue();
});

test('regular user cannot update publications they do not own', function (): void {
    $nflRegion = Region::whereSlug('nfl')->first();

    $user = User::factory()->create();
    $publication = Publication::factory()->create(['region_id' => $nflRegion->id]);

    expect($user->can('update', $publication))->toBeFalse();
});

test('user with multiple regional editor roles can update publications in all their regions', function (): void {
    $nflRegion = Region::whereSlug('nfl')->first();
    $marRegion = Region::whereSlug('mar')->first();
    $glfRegion = Region::whereSlug('glf')->first();

    $multiRegionalEditor = User::factory()->create();
    $multiRegionalEditor->assignRole([UserRole::NFL_EDITOR, UserRole::MAR_EDITOR]);

    $nflPub = Publication::factory()->create(['region_id' => $nflRegion->id]);
    $marPub = Publication::factory()->create(['region_id' => $marRegion->id]);
    $glfPub = Publication::factory()->create(['region_id' => $glfRegion->id]);

    expect($multiRegionalEditor->can('update', $nflPub))->toBeTrue();
    expect($multiRegionalEditor->can('update', $marPub))->toBeTrue();
    expect($multiRegionalEditor->can('update', $glfPub))->toBeFalse();
});
