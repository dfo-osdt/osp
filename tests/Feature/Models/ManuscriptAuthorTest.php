<?php

use App\Models\ManuscriptAuthor;

test('a user can get the manuscript authors associated with a manuscript record', function (): void {
    $user = \App\Models\User::factory()->create();
    $manuscript = \App\Models\ManuscriptRecord::factory()->has(ManuscriptAuthor::factory()->count(5))->create([
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
    $user = \App\Models\User::factory()->create();
    $manuscript = \App\Models\ManuscriptRecord::factory()->create([
        'user_id' => $user->id,
    ]);
    $author = \App\Models\Author::factory()->create();

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
    $user = \App\Models\User::factory()->create();
    $manuscript = \App\Models\ManuscriptRecord::factory()->create([
        'user_id' => $user->id,
    ]);
    $author = \App\Models\Author::factory()->create();

    $this->actingAs($user)->postJson('/api/manuscript-records/'.$manuscript->id.'/manuscript-authors', [
        'author_id' => $author->id,
    ])->assertCreated();

    $this->actingAs($user)->postJson('/api/manuscript-records/'.$manuscript->id.'/manuscript-authors', [
        'author_id' => $author->id,
    ])->assertInvalid('author_id');
});

test('a user can add a group author with a custom organization to their manuscript record', function (): void {
    $user = \App\Models\User::factory()->create();
    $manuscript = \App\Models\ManuscriptRecord::factory()->create([
        'user_id' => $user->id,
    ]);
    $author = \App\Models\Author::factory()->create();
    $organization = \App\Models\Organization::factory()->create();

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
    $user = \App\Models\User::factory()->create();
    $manuscript = \App\Models\ManuscriptRecord::factory()->create([
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
