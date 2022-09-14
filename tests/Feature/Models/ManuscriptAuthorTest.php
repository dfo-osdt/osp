<?php

use App\Models\ManuscriptAuthor;

test('a user can get the manuscript authors associated with a manuscript record', function () {
    $this->seed();

    $user = \App\Models\User::factory()->create();
    $manuscript = \App\Models\ManuscriptRecord::factory()->has(ManuscriptAuthor::factory()->count(5))->create([
        'user_id' => $user->id,
    ]);
    ray()->showQueries();
    $response = $this->actingAs($user)->getJson('/api/manuscript-records/'.$manuscript->id.'/manuscript-authors');

    $response->assertOk()->assertJsonCount(5, 'data');

    expect($response->json('data.0'))->toHaveKeys([
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
    $this->seed();

    $user = \App\Models\User::factory()->create();
    $manuscript = \App\Models\ManuscriptRecord::factory()->create([
        'user_id' => $user->id,
    ]);
    $author = \App\Models\Author::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/manuscript-records/'.$manuscript->id.'/manuscript-authors', [
        'author_id' => $author->id,
    ]);

    $response->assertCreated()->assertJson([
        'manuscript_record_id' => $manuscript->id,
        'author_id' => $author->id,
    ]);

    expect($manuscript->manuscriptAuthors()->count())->toBe(1);
});
