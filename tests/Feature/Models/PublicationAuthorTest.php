<?php

use App\Actions\CreatePublicationFromManuscript;
use App\Models\Author;
use App\Models\Journal;
use App\Models\ManuscriptRecord;
use App\Models\Organization;
use App\Models\Publication;
use App\Models\PublicationAuthor;
use App\Models\Region;
use App\Models\Sector;
use App\Models\User;

test('a user can get the publication authors associated with a publication', function (): void {
    $user = User::factory()->create();
    $publication = Publication::factory()->has(PublicationAuthor::factory()->count(5))->create([
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->getJson('/api/publications/'.$publication->id.'/publication-authors');

    $response->assertOk()->assertJsonCount(5, 'data');

    expect($response->json('data.0.data'))->toHaveKeys([
        'id',
        'publication_id',
        'author_id',
        'organization_id',
        'is_corresponding_author',
        'is_group_author',
        'organization',
        'author',
    ]);
});

test('a user can add a new publication author to their publication', function (): void {
    $user = User::factory()->create();
    $publication = Publication::factory()->create([
        'user_id' => $user->id,
    ]);
    $author = Author::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/publications/'.$publication->id.'/publication-authors', [
        'author_id' => $author->id,
        'is_corresponding_author' => true,
    ])->assertCreated();

    expect($response->json('data'))->toMatchArray([
        'publication_id' => $publication->id,
        'author_id' => $author->id,
        'is_corresponding_author' => true,
        'is_group_author' => false,
        'organization_id' => $author->organization_id,
    ])->toHaveKeys([
        'id',
        'publication_id',
        'author_id',
        'organization_id',
        'is_corresponding_author',
        'is_group_author',
        'organization',
        'author',
    ]);

    expect($publication->publicationAuthors()->count())->toBe(1);
});

test('a user cannot add the same author twice on a publication', function (): void {
    $user = User::factory()->create();
    $publication = Publication::factory()->create([
        'user_id' => $user->id,
    ]);
    $author = Author::factory()->create();

    $this->actingAs($user)->postJson('/api/publications/'.$publication->id.'/publication-authors', [
        'author_id' => $author->id,
    ])->assertCreated();

    $this->actingAs($user)->postJson('/api/publications/'.$publication->id.'/publication-authors', [
        'author_id' => $author->id,
    ])->assertInvalid('author_id');
});

test('an editor or chief editor can manage publication authors', function (): void {
    // create a publication with 3 authors
    $publication = Publication::factory()->has(PublicationAuthor::factory()->count(3))->create();
    $chiefEditor = User::factory()->chiefEditor()->create();

    // chief editor can edit any publication author
    $response = $this->actingAs($chiefEditor)->putJson('/api/publications/'.$publication->id.'/publication-authors/'.$publication->publicationAuthors()->first()->id, [
        'is_corresponding_author' => true,
    ])->assertOk();

    // try to remove an author
    $response = $this->actingAs($chiefEditor)
        ->deleteJson('/api/publications/'.$publication->id.'/publication-authors/'.$publication->publicationAuthors()->first()->id)
        ->assertStatus(204);

    // check that publication has only 2 authors
    expect($publication->publicationAuthors()->count())->toBe(2);

    // try again with an editor
    $editor = User::factory()->editor()->create();

    // editor can edit any publication author
    $response = $this->actingAs($editor)->putJson('/api/publications/'.$publication->id.'/publication-authors/'.$publication->publicationAuthors()->first()->id, [
        'is_corresponding_author' => true,
    ])->assertOk();

    // try to remove an author
    $response = $this->actingAs($editor)
        ->deleteJson('/api/publications/'.$publication->id.'/publication-authors/'.$publication->publicationAuthors()->first()->id)
        ->assertStatus(204);

    // check that publication has only 1 author
    expect($publication->publicationAuthors()->count())->toBe(1);

});

test('a user can remove and re-add the same author to a publication', function (): void {
    $user = User::factory()->create();
    $publication = Publication::factory()->create([
        'user_id' => $user->id,
    ]);
    $author = Author::factory()->create();

    // Add an author
    $response = $this->actingAs($user)->postJson('/api/publications/'.$publication->id.'/publication-authors', [
        'author_id' => $author->id,
        'is_corresponding_author' => true,
    ])->assertCreated();

    $publicationAuthorId = $response->json('data.id');
    expect($publication->publicationAuthors()->count())->toBe(1);

    // Remove the author
    $this->actingAs($user)
        ->deleteJson('/api/publications/'.$publication->id.'/publication-authors/'.$publicationAuthorId)
        ->assertStatus(204);

    expect($publication->publicationAuthors()->count())->toBe(0);

    // Verify the author is force deleted (not soft deleted)
    expect(PublicationAuthor::withTrashed()->find($publicationAuthorId))->toBeNull();

    // Re-add the same author (should succeed)
    $response = $this->actingAs($user)->postJson('/api/publications/'.$publication->id.'/publication-authors', [
        'author_id' => $author->id,
        'is_corresponding_author' => false,
    ])->assertCreated();

    expect($response->json('data'))->toMatchArray([
        'publication_id' => $publication->id,
        'author_id' => $author->id,
        'is_corresponding_author' => false,
        'organization_id' => $author->organization_id,
    ]);

    expect($publication->publicationAuthors()->count())->toBe(1);
});

test('a user can add a group author with a custom organization to their publication', function (): void {
    $user = User::factory()->create();
    $publication = Publication::factory()->create([
        'user_id' => $user->id,
    ]);
    $author = Author::factory()->create();
    $organization = Organization::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/publications/'.$publication->id.'/publication-authors', [
        'author_id' => $author->id,
        'is_corresponding_author' => false,
        'is_group_author' => true,
        'organization_id' => $organization->id,
    ])->assertCreated();

    expect($response->json('data'))->toMatchArray([
        'publication_id' => $publication->id,
        'author_id' => $author->id,
        'is_corresponding_author' => false,
        'is_group_author' => true,
        'organization_id' => $organization->id,
    ]);

    // organization should differ from author's profile org
    expect($response->json('data.organization_id'))->not->toBe($author->organization_id);
});

test('a user can update a publication author to be an organizational author', function (): void {
    $user = User::factory()->create();
    $publication = Publication::factory()->create([
        'user_id' => $user->id,
    ]);
    $publicationAuthor = PublicationAuthor::factory()->create([
        'publication_id' => $publication->id,
    ]);

    $response = $this->actingAs($user)->putJson('/api/publications/'.$publication->id.'/publication-authors/'.$publicationAuthor->id, [
        'is_group_author' => true,
    ])->assertOk();

    expect($response->json('data.is_group_author'))->toBeTrue();
});

test('adding a DFO author to a publication records their sector, region and emeritus status', function (): void {
    $user = User::factory()->create();
    $publication = Publication::factory()->create(['user_id' => $user->id]);
    $author = Author::factory()->withCompleteProfile()->create(['is_emeritus' => true]);

    $response = $this->actingAs($user)->postJson('/api/publications/'.$publication->id.'/publication-authors', [
        'author_id' => $author->id,
    ])->assertCreated();

    expect(PublicationAuthor::query()->find($response->json('data.id')))
        ->sector_id->toBe($author->sector_id)
        ->region_id->toBe($author->region_id)
        ->is_emeritus->toBeTrue();
});

test('adding a DFO author with a non-DFO affiliation to a publication records no sector, region or emeritus status', function (): void {
    $user = User::factory()->create();
    $publication = Publication::factory()->create(['user_id' => $user->id]);
    $author = Author::factory()->withCompleteProfile()->create();

    $response = $this->actingAs($user)->postJson('/api/publications/'.$publication->id.'/publication-authors', [
        'author_id' => $author->id,
        'organization_id' => Organization::factory()->create()->id,
    ])->assertCreated();

    expect(PublicationAuthor::query()->find($response->json('data.id')))
        ->sector_id->toBeNull()
        ->region_id->toBeNull()
        ->is_emeritus->toBeNull();
});

test('moving a publication author out of DFO clears their sector, region and emeritus status', function (): void {
    $user = User::factory()->create();
    $publication = Publication::factory()->create(['user_id' => $user->id]);
    $author = Author::factory()->withCompleteProfile()->create();
    $added = $this->actingAs($user)->postJson('/api/publications/'.$publication->id.'/publication-authors', [
        'author_id' => $author->id,
    ])->assertCreated();

    $this->actingAs($user)->putJson('/api/publications/'.$publication->id.'/publication-authors/'.$added->json('data.id'), [
        'organization_id' => Organization::factory()->create()->id,
    ])->assertOk();

    expect(PublicationAuthor::query()->find($added->json('data.id')))
        ->sector_id->toBeNull()
        ->region_id->toBeNull()
        ->is_emeritus->toBeNull();
});

test('a publication created from a manuscript keeps the sector and region recorded on the manuscript', function (): void {
    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->create(['user_id' => $user->id]);
    $author = Author::factory()->withCompleteProfile()->create();
    $this->actingAs($user)->postJson('/api/manuscript-records/'.$manuscript->id.'/manuscript-authors', [
        'author_id' => $author->id,
    ])->assertCreated();
    $recorded = ['sector_id' => $author->sector_id, 'region_id' => $author->region_id];
    $author->update([
        'sector_id' => Sector::factory()->create()->id,
        'region_id' => Region::query()->whereKeyNot($author->region_id)->value('id'),
    ]);

    $publication = CreatePublicationFromManuscript::handle($manuscript, Journal::factory()->create());

    expect($publication->publicationAuthors()->where('author_id', $author->id)->sole())
        ->sector_id->toBe($recorded['sector_id'])
        ->region_id->toBe($recorded['region_id'])
        ->is_emeritus->toBeFalse();
});
