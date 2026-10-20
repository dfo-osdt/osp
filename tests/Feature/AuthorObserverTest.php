<?php

use App\Models\Author;
use App\Models\ManuscriptAuthor;
use App\Models\Organization;
use App\Models\PublicationAuthor;
use App\Models\Region;
use App\Models\Sector;

test('first filling the sector and region backfills the empty DFO authorship snapshots', function (): void {
    $author = Author::factory()->internal()->create();
    $dfoId = Organization::getDefaultOrganization()->id;
    $manuscriptAuthor = ManuscriptAuthor::factory()->for($author)->create(['organization_id' => $dfoId]);
    $publicationAuthor = PublicationAuthor::factory()->for($author)->create(['organization_id' => $dfoId]);
    $sector = Sector::factory()->create();
    $regionId = Region::query()->value('id');

    $author->update(['sector_id' => $sector->id, 'region_id' => $regionId]);

    expect($manuscriptAuthor->refresh())->sector_id->toBe($sector->id)->region_id->toBe($regionId);
    expect($publicationAuthor->refresh())->sector_id->toBe($sector->id)->region_id->toBe($regionId);
});

test('the first fill backfill skips non-DFO authorships', function (): void {
    $author = Author::factory()->internal()->create();
    $externalAuthorship = ManuscriptAuthor::factory()->for($author)->create([
        'organization_id' => Organization::factory()->create()->id,
    ]);

    $author->update(['sector_id' => Sector::factory()->create()->id]);

    expect($externalAuthorship->refresh()->sector_id)->toBeNull();
});

test('the first fill backfill keeps snapshots that already hold a value', function (): void {
    $author = Author::factory()->internal()->create();
    $existingSector = Sector::factory()->create();
    $manuscriptAuthor = ManuscriptAuthor::factory()->for($author)->create([
        'organization_id' => Organization::getDefaultOrganization()->id,
        'sector_id' => $existingSector->id,
    ]);

    $author->update(['sector_id' => Sector::factory()->create()->id]);

    expect($manuscriptAuthor->refresh()->sector_id)->toBe($existingSector->id);
});

test('changing an already filled sector does not backfill empty snapshots', function (): void {
    $author = Author::factory()->withCompleteProfile()->create();
    $manuscriptAuthor = ManuscriptAuthor::factory()->for($author)->create([
        'organization_id' => Organization::getDefaultOrganization()->id,
    ]);
    $manuscriptAuthor->updateQuietly(['sector_id' => null]);

    $author->update(['sector_id' => Sector::factory()->create()->id]);

    expect($manuscriptAuthor->refresh()->sector_id)->toBeNull();
});

test('an author leaving DFO loses their sector, region and emeritus status', function (): void {
    $author = Author::factory()->withCompleteProfile()->create(['is_emeritus' => true]);

    $author->update(['organization_id' => Organization::factory()->create()->id]);

    expect($author->refresh())
        ->sector_id->toBeNull()
        ->region_id->toBeNull()
        ->is_emeritus->toBeFalse();
});

test('an author leaving DFO keeps the snapshots of existing authorships', function (): void {
    $author = Author::factory()->withCompleteProfile()->create();
    $manuscriptAuthor = ManuscriptAuthor::factory()->for($author)->create([
        'organization_id' => Organization::getDefaultOrganization()->id,
    ]);

    $author->update(['organization_id' => Organization::factory()->create()->id]);

    expect($manuscriptAuthor->refresh()->sector_id)->not->toBeNull();
});
