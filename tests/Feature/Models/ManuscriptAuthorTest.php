<?php

use App\Models\Author;
use App\Models\ManuscriptAuthor;
use App\Models\ManuscriptRecord;
use App\Models\Organization;
use App\Models\Sector;
use App\Models\User;

test('a user can get the manuscript authors associated with a manuscript record', function (): void {
    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->has(ManuscriptAuthor::factory()->count(5))->create([
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->getJson('/api/manuscript-records/'.$manuscript->id.'/manuscript-authors');

    $response->assertOk()->assertJsonCount(5, 'data');

    expect($response->json('data.0.data'))->toHaveKeys([
        'id',
        'manuscript_record_id',
        'author_id',
        'organization_id',
        'is_corresponding_author',
        'is_group_author',
        'organization',
        'author',
    ]);
});

test('a user can add a new manuscript author to their manuscript record', function (): void {
    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->create([
        'user_id' => $user->id,
    ]);
    $author = Author::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/manuscript-records/'.$manuscript->id.'/manuscript-authors', [
        'author_id' => $author->id,
        'is_corresponding_author' => true,
    ])->assertCreated();

    expect($response->json('data'))->toMatchArray([
        'manuscript_record_id' => $manuscript->id,
        'author_id' => $author->id,
        'is_corresponding_author' => true,
        'is_group_author' => false,
        'organization_id' => $author->organization_id,
    ])->toHaveKeys([
        'id',
        'manuscript_record_id',
        'author_id',
        'organization_id',
        'is_corresponding_author',
        'is_group_author',
        'organization',
        'author',
    ]);

    // this is there as if it doesn't exist we can't tell the frontend
    // if the user can update or delete the record (e.g. AuthorChip)
    // dd('data', $response->json('can'));

    expect($response->json('can'))->toMatchArray([
        'update' => true,
        'delete' => true,
    ]);

    expect($manuscript->manuscriptAuthors()->count())->toBe(1);
});

test('a user cannot add the same author twice to a manuscript record', function (): void {
    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->create([
        'user_id' => $user->id,
    ]);
    $author = Author::factory()->create();

    $this->actingAs($user)->postJson('/api/manuscript-records/'.$manuscript->id.'/manuscript-authors', [
        'author_id' => $author->id,
    ])->assertCreated();

    $this->actingAs($user)->postJson('/api/manuscript-records/'.$manuscript->id.'/manuscript-authors', [
        'author_id' => $author->id,
    ])->assertInvalid('author_id');
});

test('a user can add a group author with a custom organization to their manuscript record', function (): void {
    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->create([
        'user_id' => $user->id,
    ]);
    $author = Author::factory()->create();
    $organization = Organization::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/manuscript-records/'.$manuscript->id.'/manuscript-authors', [
        'author_id' => $author->id,
        'is_corresponding_author' => false,
        'is_group_author' => true,
        'organization_id' => $organization->id,
    ])->assertCreated();

    expect($response->json('data'))->toMatchArray([
        'manuscript_record_id' => $manuscript->id,
        'author_id' => $author->id,
        'is_corresponding_author' => false,
        'is_group_author' => true,
        'organization_id' => $organization->id,
    ]);

    // organization should differ from author's profile org
    expect($response->json('data.organization_id'))->not->toBe($author->organization_id);
});

test('a user can update a manuscript author to be an organizational author', function (): void {
    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->create([
        'user_id' => $user->id,
    ]);
    $manuscriptAuthor = ManuscriptAuthor::factory()->create([
        'manuscript_record_id' => $manuscript->id,
    ]);

    $response = $this->actingAs($user)->putJson('/api/manuscript-records/'.$manuscript->id.'/manuscript-authors/'.$manuscriptAuthor->id, [
        'is_group_author' => true,
    ])->assertOk();

    expect($response->json('data.is_group_author'))->toBeTrue();
});

test('adding an independent author to a manuscript record keeps them independent', function (): void {
    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->create([
        'user_id' => $user->id,
    ]);
    $author = Author::factory()->independent()->create();

    $this->actingAs($user)->postJson('/api/manuscript-records/'.$manuscript->id.'/manuscript-authors', [
        'author_id' => $author->id,
    ])->assertCreated()->assertJson([
        'data' => [
            'organization_id' => Organization::getIndependentOrganization()->id,
            'is_independent' => true,
        ],
    ]);
});

test('adding a DFO author to a manuscript records their sector, region and emeritus status', function (): void {
    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->create(['user_id' => $user->id]);
    $author = Author::factory()->withCompleteProfile()->create(['is_emeritus' => true]);

    $response = $this->actingAs($user)->postJson('/api/manuscript-records/'.$manuscript->id.'/manuscript-authors', [
        'author_id' => $author->id,
    ])->assertCreated();

    expect(ManuscriptAuthor::query()->find($response->json('data.id')))
        ->sector_id->toBe($author->sector_id)
        ->region_id->toBe($author->region_id)
        ->is_emeritus->toBeTrue();
});

test('adding a DFO author with a non-DFO affiliation to a manuscript records no sector, region or emeritus status', function (): void {
    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->create(['user_id' => $user->id]);
    $author = Author::factory()->withCompleteProfile()->create();

    $response = $this->actingAs($user)->postJson('/api/manuscript-records/'.$manuscript->id.'/manuscript-authors', [
        'author_id' => $author->id,
        'organization_id' => Organization::factory()->create()->id,
    ])->assertCreated();

    expect(ManuscriptAuthor::query()->find($response->json('data.id')))
        ->sector_id->toBeNull()
        ->region_id->toBeNull()
        ->is_emeritus->toBeNull();
});

test('moving a manuscript author into DFO records their sector, region and emeritus status', function (): void {
    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->create(['user_id' => $user->id]);
    $author = Author::factory()->withCompleteProfile()->create();
    $manuscriptAuthor = ManuscriptAuthor::factory()->for($manuscript)->for($author)->create();

    $this->actingAs($user)->putJson('/api/manuscript-records/'.$manuscript->id.'/manuscript-authors/'.$manuscriptAuthor->id, [
        'organization_id' => $author->organization_id,
    ])->assertOk();

    expect($manuscriptAuthor->refresh())
        ->sector_id->toBe($author->sector_id)
        ->region_id->toBe($author->region_id)
        ->is_emeritus->toBeFalse();
});

test('moving a manuscript author out of DFO clears their sector, region and emeritus status', function (): void {
    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->create(['user_id' => $user->id]);
    $author = Author::factory()->withCompleteProfile()->create();
    $added = $this->actingAs($user)->postJson('/api/manuscript-records/'.$manuscript->id.'/manuscript-authors', [
        'author_id' => $author->id,
    ])->assertCreated();

    $this->actingAs($user)->putJson('/api/manuscript-records/'.$manuscript->id.'/manuscript-authors/'.$added->json('data.id'), [
        'organization_id' => Organization::factory()->create()->id,
    ])->assertOk();

    expect(ManuscriptAuthor::query()->find($added->json('data.id')))
        ->sector_id->toBeNull()
        ->region_id->toBeNull()
        ->is_emeritus->toBeNull();
});

test('updating a manuscript author without changing the affiliation keeps their recorded sector', function (): void {
    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->create(['user_id' => $user->id]);
    $author = Author::factory()->withCompleteProfile()->create();
    $added = $this->actingAs($user)->postJson('/api/manuscript-records/'.$manuscript->id.'/manuscript-authors', [
        'author_id' => $author->id,
    ])->assertCreated();
    $manuscriptAuthor = ManuscriptAuthor::query()->find($added->json('data.id'));
    $recordedSectorId = $author->sector_id;
    $author->update(['sector_id' => Sector::factory()->create()->id]);

    $this->actingAs($user)->putJson('/api/manuscript-records/'.$manuscript->id.'/manuscript-authors/'.$manuscriptAuthor->id, [
        'organization_id' => $author->organization_id,
        'is_corresponding_author' => true,
    ])->assertOk();

    expect($manuscriptAuthor->refresh()->sector_id)->toBe($recordedSectorId);
});
