<?php

use App\Models\Author;
use App\Models\Publication;
use App\Models\PublicationAuthor;
use App\Models\User;

test('authenticated user can get author publications', function () {
    $user = User::factory()->create();
    $author = Author::factory()->create();
    $publications = Publication::factory()->count(3)->published()->create();

    // Associate publications with author
    foreach ($publications as $publication) {
        PublicationAuthor::factory()->create([
            'publication_id' => $publication->id,
            'author_id' => $author->id,
        ]);
    }

    $response = $this->actingAs($user)->getJson("/api/authors/{$author->id}/publications");

    $response->assertOk();
    $response->assertJsonCount(3, 'data');

});

test('unauthenticated user cannot access author publications', function () {
    $author = Author::factory()->create();

    $response = $this->getJson("/api/authors/{$author->id}/publications");

    $response->assertUnauthorized();
});

test('returns only published publications for author', function () {
    $user = User::factory()->create();
    $author = Author::factory()->create();

    // Create published publications
    $publishedPublications = Publication::factory()->count(2)->published()->create();

    // Create non-published publications
    $acceptedPublications = Publication::factory()->count(2)->create();

    // Associate all publications with author
    foreach ([...$publishedPublications, ...$acceptedPublications] as $publication) {
        PublicationAuthor::factory()->create([
            'publication_id' => $publication->id,
            'author_id' => $author->id,
        ]);
    }

    $response = $this->actingAs($user)->getJson("/api/authors/{$author->id}/publications");

    $response->assertOk();
    // Should only return the 2 published publications, not the accepted ones
    $response->assertJsonCount(2, 'data');

    // Verify all returned publications are published
    $returnedPublications = $response->json('data');
    foreach ($returnedPublications as $pubData) {
        expect($pubData['data']['status'])->toBe('published');
    }
});

test('supports pagination with limit parameter', function () {
    $user = User::factory()->create();
    $author = Author::factory()->create();
    $publications = Publication::factory()->count(5)->published()->create();

    // Associate publications with author
    foreach ($publications as $publication) {
        PublicationAuthor::factory()->create([
            'publication_id' => $publication->id,
            'author_id' => $author->id,
        ]);
    }

    $response = $this->actingAs($user)->getJson("/api/authors/{$author->id}/publications?limit=3");

    $response->assertOk();
    $response->assertJsonCount(3, 'data');
    expect($response->json('meta.total'))->toBe(5);
    expect($response->json('meta.per_page'))->toBe(3);
});

test('returns empty collection for author with no publications', function () {
    $user = User::factory()->create();
    $author = Author::factory()->create();

    $response = $this->actingAs($user)->getJson("/api/authors/{$author->id}/publications");

    $response->assertOk();
    $response->assertJsonCount(0, 'data');
    expect($response->json('meta.total'))->toBe(0);
});

test('returns 404 for non-existent author', function () {
    $user = User::factory()->create();
    $nonExistentAuthorId = 999999;

    $response = $this->actingAs($user)->getJson("/api/authors/{$nonExistentAuthorId}/publications");

    $response->assertNotFound();
});

test('publications are ordered by published date descending', function () {
    $user = User::factory()->create();
    $author = Author::factory()->create();

    // Create publications with different published dates
    $oldPublication = Publication::factory()->published()->create([
        'published_on' => now()->subYears(2),
        'title' => 'Old Publication',
    ]);
    $recentPublication = Publication::factory()->published()->create([
        'published_on' => now()->subMonths(1),
        'title' => 'Recent Publication',
    ]);
    $newestPublication = Publication::factory()->published()->create([
        'published_on' => now()->subDays(1),
        'title' => 'Newest Publication',
    ]);

    // Associate publications with author
    foreach ([$oldPublication, $recentPublication, $newestPublication] as $publication) {
        PublicationAuthor::factory()->create([
            'publication_id' => $publication->id,
            'author_id' => $author->id,
        ]);
    }

    $response = $this->actingAs($user)->getJson("/api/authors/{$author->id}/publications");

    $response->assertOk();
    $returnedTitles = collect($response->json('data'))->pluck('data.title')->toArray();

    // Should be ordered newest first
    expect($returnedTitles)->toEqual([
        'Newest Publication',
        'Recent Publication',
        'Old Publication',
    ]);
});

test('includes proper eager loaded relationships', function () {
    $user = User::factory()->create();
    $author = Author::factory()->create();
    $publication = Publication::factory()->published()->create();

    PublicationAuthor::factory()->create([
        'publication_id' => $publication->id,
        'author_id' => $author->id,
    ]);

    $response = $this->actingAs($user)->getJson("/api/authors/{$author->id}/publications");

    $response->assertOk();

    // Verify all expected relationships are present
    $publicationData = $response->json('data.0.data');
    expect($publicationData)->toHaveKeys([
        'journal',
        'user',
        'publication_authors',
        'manuscript_record',
    ]);

    // Verify nested relationships are loaded
    expect($publicationData['journal']['data'])->toHaveKeys(['id', 'title']);
    expect($publicationData['publication_authors'][0]['data']['author']['data'])
        ->toHaveKeys(['id', 'first_name']);
});
