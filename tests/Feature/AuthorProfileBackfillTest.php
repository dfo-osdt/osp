<?php

use App\Models\Author;
use App\Models\ManuscriptAuthor;
use App\Models\ManuscriptRecord;
use App\Models\Organization;
use App\Models\Publication;
use App\Models\PublicationAuthor;
use App\Models\Region;
use App\Models\Sector;
use App\Models\User;

/**
 * Add the user's own author to a new manuscript and a new publication
 * through the API, the way authorships are created in the app.
 *
 * @return array{0: ManuscriptAuthor, 1: PublicationAuthor}
 */
function addOwnAuthorships(User $user): array
{
    $manuscript = ManuscriptRecord::factory()->create(['user_id' => $user->id]);
    $publication = Publication::factory()->create(['user_id' => $user->id]);

    $manuscriptAuthorId = test()->actingAs($user)
        ->postJson('/api/manuscript-records/'.$manuscript->id.'/manuscript-authors', ['author_id' => $user->author->id])
        ->assertCreated()
        ->json('data.id');
    $publicationAuthorId = test()->actingAs($user)
        ->postJson('/api/publications/'.$publication->id.'/publication-authors', ['author_id' => $user->author->id])
        ->assertCreated()
        ->json('data.id');

    return [ManuscriptAuthor::query()->find($manuscriptAuthorId), PublicationAuthor::query()->find($publicationAuthorId)];
}

test('first filling the sector and region fills them on the author\'s earlier manuscripts and publications', function (): void {
    $user = User::factory()->create();
    [$manuscriptAuthor, $publicationAuthor] = addOwnAuthorships($user);
    $sector = Sector::factory()->create();
    $regionId = Region::query()->value('id');

    $this->actingAs($user)->putJson('api/authors/'.$user->author->id, [
        'sector_id' => $sector->id,
        'region_id' => $regionId,
    ])->assertOk();

    expect($manuscriptAuthor->refresh())->sector_id->toBe($sector->id)->region_id->toBe($regionId);
    expect($publicationAuthor->refresh())->sector_id->toBe($sector->id)->region_id->toBe($regionId);
});

test('first filling the sector does not touch the author\'s non-DFO manuscripts', function (): void {
    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->create(['user_id' => $user->id]);
    $externalAuthorshipId = $this->actingAs($user)->postJson('/api/manuscript-records/'.$manuscript->id.'/manuscript-authors', [
        'author_id' => $user->author->id,
        'organization_id' => Organization::factory()->create()->id,
    ])->assertCreated()->json('data.id');

    $this->actingAs($user)->putJson('api/authors/'.$user->author->id, [
        'sector_id' => Sector::factory()->create()->id,
        'region_id' => Region::query()->value('id'),
    ])->assertOk();

    expect(ManuscriptAuthor::query()->find($externalAuthorshipId)->sector_id)->toBeNull();
});

test('changing an already filled sector does not change earlier manuscripts and publications', function (): void {
    $user = User::factory()->create();
    $originalSector = Sector::factory()->create();
    $this->actingAs($user)->putJson('api/authors/'.$user->author->id, [
        'sector_id' => $originalSector->id,
        'region_id' => Region::query()->value('id'),
    ])->assertOk();
    [$manuscriptAuthor, $publicationAuthor] = addOwnAuthorships($user);

    $this->actingAs($user)->putJson('api/authors/'.$user->author->id, [
        'sector_id' => Sector::factory()->create()->id,
    ])->assertOk();

    expect($manuscriptAuthor->refresh()->sector_id)->toBe($originalSector->id);
    expect($publicationAuthor->refresh()->sector_id)->toBe($originalSector->id);
});

test('an author moved out of DFO loses their sector, region and emeritus status', function (): void {
    $editor = User::factory()->editor()->create();
    $author = Author::factory()->withCompleteProfile()->create(['is_emeritus' => true]);

    $this->actingAs($editor)->putJson('api/authors/'.$author->id, [
        'organization_id' => Organization::factory()->create()->id,
    ])->assertOk();

    expect($author->refresh())
        ->sector_id->toBeNull()
        ->region_id->toBeNull()
        ->is_emeritus->toBeFalse();
});

test('an author moved out of DFO keeps the sector recorded on earlier manuscripts', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user)->putJson('api/authors/'.$user->author->id, [
        'sector_id' => Sector::factory()->create()->id,
        'region_id' => Region::query()->value('id'),
    ])->assertOk();
    [$manuscriptAuthor] = addOwnAuthorships($user);
    $recordedSectorId = $manuscriptAuthor->sector_id;

    $this->actingAs(User::factory()->editor()->create())->putJson('api/authors/'.$user->author->id, [
        'organization_id' => Organization::factory()->create()->id,
    ])->assertOk();

    expect($manuscriptAuthor->refresh()->sector_id)->toBe($recordedSectorId)->not->toBeNull();
});
