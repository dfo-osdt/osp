<?php

use App\Models\PublicationAuthor;

test('a user can get the publication authors associated with a publication', function () {
    $user = \App\Models\User::factory()->create();
    $publication = \App\Models\Publication::factory()->has(PublicationAuthor::factory()->count(5))->create([
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
        'organization',
        'author',
    ]);
});

test('a user can add a new publication author to their publication', function () {
    $user = \App\Models\User::factory()->create();
    $publication = \App\Models\Publication::factory()->create([
        'user_id' => $user->id,
    ]);
    $author = \App\Models\Author::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/publications/'.$publication->id.'/publication-authors', [
        'author_id' => $author->id,
        'is_corresponding_author' => true,
    ])->assertCreated();

    expect($response->json('data'))->toMatchArray([
        'publication_id' => $publication->id,
        'author_id' => $author->id,
        'is_corresponding_author' => true,
        'organization_id' => $author->organization_id,
    ])->toHaveKeys([
        'id',
        'publication_id',
        'author_id',
        'organization_id',
        'is_corresponding_author',
        'organization',
        'author',
    ]);

    expect($publication->publicationAuthors()->count())->toBe(1);
});

test('a user cannot add the same author twice on a publication', function () {
    $user = \App\Models\User::factory()->create();
    $publication = \App\Models\Publication::factory()->create([
        'user_id' => $user->id,
    ]);
    $author = \App\Models\Author::factory()->create();

    $this->actingAs($user)->postJson('/api/publications/'.$publication->id.'/publication-authors', [
        'author_id' => $author->id,
    ])->assertCreated();

    $this->actingAs($user)->postJson('/api/publications/'.$publication->id.'/publication-authors', [
        'author_id' => $author->id,
    ])->assertInvalid('author_id');
});
