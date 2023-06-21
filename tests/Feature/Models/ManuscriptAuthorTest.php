<?php

use App\Models\ManuscriptAuthor;

test('a user can get the manuscript authors associated with a manuscript record', function () {
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
        'organization',
        'author',
    ]);
});

test('a user can add a new manuscript author to their manuscript record', function () {
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
        'organization_id' => $author->organization_id,
    ])->toHaveKeys([
        'id',
        'manuscript_record_id',
        'author_id',
        'organization_id',
        'is_corresponding_author',
        'organization',
        'author',
    ]);

    // this is there as if it doesn't exist we can't tell the frontend
    // if the user can update or delete the record (e.g. AuthorChip)
    //dd('data', $response->json('can'));

    expect($response->json('can'))->toMatchArray([
        'update' => true,
        'delete' => true,
    ]);

    expect($manuscript->manuscriptAuthors()->count())->toBe(1);
});

test('a user cannot add the same author twice to a manuscript record', function () {
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
