<?php

use App\Actions\CreatePublicationFromManuscript;
use App\Models\Author;
use App\Models\Journal;
use App\Models\ManuscriptAuthor;
use App\Models\ManuscriptRecord;
use App\Models\Organization;
use App\Models\PublicationAuthor;
use App\Models\Region;
use App\Models\Sector;

test('a DFO authorship copies the author sector, region and emeritus status when created', function (string $authorshipClass): void {
    $author = Author::factory()->withCompleteProfile()->create(['is_emeritus' => true]);

    $authorship = $authorshipClass::factory()->for($author)->create([
        'organization_id' => Organization::getDefaultOrganization()->id,
    ]);

    expect($authorship->refresh())
        ->sector_id->toBe($author->sector_id)
        ->region_id->toBe($author->region_id)
        ->is_emeritus->toBeTrue();
})->with([
    'manuscript author' => ManuscriptAuthor::class,
    'publication author' => PublicationAuthor::class,
]);

test('a non-DFO authorship never holds a sector, region or emeritus status', function (string $authorshipClass): void {
    $author = Author::factory()->withCompleteProfile()->create();

    $authorship = $authorshipClass::factory()->for($author)->create([
        'organization_id' => Organization::factory()->create()->id,
        'sector_id' => $author->sector_id,
    ]);

    expect($authorship->refresh())
        ->sector_id->toBeNull()
        ->region_id->toBeNull()
        ->is_emeritus->toBeNull();
})->with([
    'manuscript author' => ManuscriptAuthor::class,
    'publication author' => PublicationAuthor::class,
]);

test('moving an authorship out of DFO clears its snapshot', function (): void {
    $author = Author::factory()->withCompleteProfile()->create();
    $manuscriptAuthor = ManuscriptAuthor::factory()->for($author)->create([
        'organization_id' => Organization::getDefaultOrganization()->id,
    ]);

    $manuscriptAuthor->update(['organization_id' => Organization::factory()->create()->id]);

    expect($manuscriptAuthor->refresh())
        ->sector_id->toBeNull()
        ->region_id->toBeNull()
        ->is_emeritus->toBeNull();
});

test('moving an authorship into DFO copies the author profile', function (): void {
    $author = Author::factory()->withCompleteProfile()->create();
    $manuscriptAuthor = ManuscriptAuthor::factory()->for($author)->create([
        'organization_id' => Organization::factory()->create()->id,
    ]);

    $manuscriptAuthor->update(['organization_id' => Organization::getDefaultOrganization()->id]);

    expect($manuscriptAuthor->refresh())
        ->sector_id->toBe($author->sector_id)
        ->region_id->toBe($author->region_id)
        ->is_emeritus->toBeFalse();
});

test('an authorship snapshot does not follow later profile changes', function (): void {
    $author = Author::factory()->withCompleteProfile()->create();
    $manuscriptAuthor = ManuscriptAuthor::factory()->for($author)->create([
        'organization_id' => Organization::getDefaultOrganization()->id,
    ]);
    $originalSectorId = $author->sector_id;

    $author->update(['sector_id' => Sector::factory()->create()->id, 'is_emeritus' => true]);

    expect($manuscriptAuthor->refresh())
        ->sector_id->toBe($originalSectorId)
        ->is_emeritus->toBeFalse();
});

test('a publication created from a manuscript keeps the manuscript author snapshot', function (): void {
    $manuscript = ManuscriptRecord::factory()->create();
    $author = Author::factory()->withCompleteProfile()->create();
    $manuscriptAuthor = ManuscriptAuthor::factory()->for($author)->for($manuscript)->create([
        'organization_id' => Organization::getDefaultOrganization()->id,
    ]);
    $author->update([
        'sector_id' => Sector::factory()->create()->id,
        'region_id' => Region::query()->whereKeyNot($author->region_id)->value('id'),
    ]);

    $publication = CreatePublicationFromManuscript::handle($manuscript, Journal::factory()->create());

    $publicationAuthor = $publication->publicationAuthors()->where('author_id', $author->id)->sole();
    expect($publicationAuthor)
        ->sector_id->toBe($manuscriptAuthor->sector_id)
        ->region_id->toBe($manuscriptAuthor->region_id)
        ->is_emeritus->toBeFalse();
});
