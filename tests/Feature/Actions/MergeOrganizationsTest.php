<?php

use App\Actions\Organizations\MergeOrganizations;
use App\Models\Author;
use App\Models\AuthorEmployment;
use App\Models\Funder;
use App\Models\ManuscriptAuthor;
use App\Models\ManuscriptRecord;
use App\Models\Organization;
use App\Models\Publication;
use App\Models\PublicationAuthor;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it merges organizations and updates all related records', function () {
    $sourceOrg = Organization::factory()->create(['name_en' => 'Source Org']);
    $targetOrg = Organization::factory()->create(['name_en' => 'Target Org']);

    $author = Author::factory()->create(['organization_id' => $sourceOrg->id]);
    $manuscriptRecord = ManuscriptRecord::factory()->create();
    $manuscriptAuthor = ManuscriptAuthor::factory()->create([
        'manuscript_record_id' => $manuscriptRecord->id,
        'author_id' => $author->id,
        'organization_id' => $sourceOrg->id,
    ]);
    $publication = Publication::factory()->create();
    $publicationAuthor = PublicationAuthor::factory()->create([
        'publication_id' => $publication->id,
        'author_id' => $author->id,
        'organization_id' => $sourceOrg->id,
    ]);
    $authorEmployment = AuthorEmployment::factory()->create([
        'author_id' => $author->id,
        'organization_id' => $sourceOrg->id,
    ]);
    $funder = Funder::factory()->create(['organization_id' => $sourceOrg->id]);

    $result = MergeOrganizations::handle($sourceOrg, $targetOrg);

    expect($result)->toBeTrue();

    expect($author->fresh()->organization_id)->toBe($targetOrg->id);
    expect($manuscriptAuthor->fresh()->organization_id)->toBe($targetOrg->id);
    expect($publicationAuthor->fresh()->organization_id)->toBe($targetOrg->id);
    expect($authorEmployment->fresh()->organization_id)->toBe($targetOrg->id);
    expect($funder->fresh()->organization_id)->toBe($targetOrg->id);

    expect(Organization::find($sourceOrg->id))->toBeNull();
});

test('it throws exception when trying to merge organization with itself', function () {
    $organization = Organization::factory()->create();

    MergeOrganizations::handle($organization, $organization);
})->throws(\InvalidArgumentException::class, 'Source and target organizations cannot be the same.');

test('it rolls back on error and preserves data integrity', function () {
    $sourceOrg = Organization::factory()->create(['name_en' => 'Source Org']);
    $targetOrg = Organization::factory()->create(['name_en' => 'Target Org']);

    $author = Author::factory()->create(['organization_id' => $sourceOrg->id]);

    $targetOrg->delete();

    try {
        MergeOrganizations::handle($sourceOrg, $targetOrg);
    } catch (\Exception $e) {
    }

    expect($author->fresh()->organization_id)->toBe($sourceOrg->id);
    expect(Organization::find($sourceOrg->id))->not->toBeNull();
});

test('it handles organizations with no related records', function () {
    $sourceOrg = Organization::factory()->create(['name_en' => 'Empty Source Org']);
    $targetOrg = Organization::factory()->create(['name_en' => 'Target Org']);

    $result = MergeOrganizations::handle($sourceOrg, $targetOrg);

    expect($result)->toBeTrue();
    expect(Organization::find($sourceOrg->id))->toBeNull();
});
