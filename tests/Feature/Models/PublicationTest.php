<?php

use App\Enums\PublicationStatus;
use App\Models\Journal;
use App\Models\Publication;
use App\Models\User;

/** Test that a user can query publications */
test('a user can get a list of publications', function () {
    $user = User::factory()->create();
    $publications = Publication::factory()->count(5)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->getJson('/api/publications');

    $response->assertOk();
    $response->assertJsonCount(5, 'data');
});

test('a user can get a list of publications with a filter', function () {
    $user = User::factory()->create();
    $publications = Publication::factory()->count(5)->create(['user_id' => $user->id]);
    $openAccessPublication = Publication::factory()->openAccess()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->getJson('/api/publications?filter[open_access]=1');

    $response->assertOk();
    $response->assertJsonCount(1, 'data');
    ray($response->json('data'));
    expect($response->json('data.0.data.id'))->toBe($openAccessPublication->id);
});

/** Test that a user can get a list of their publications */
test('a user can get a list of their publications', function () {
    $user = User::factory()->create();
    $publications = Publication::factory()->count(5)->create(['user_id' => $user->id]);
    Publication::factory()->count(5)->create();

    $response = $this->actingAs($user)->getJson('/api/my/publications');

    $response->assertOk();
    $response->assertJsonCount(5, 'data');
});

/** Test that a user can create a new publication without a manuscript attached */
test('a user can create a new publication without a manuscript attached', function () {
    $user = User::factory()->create();
    $journal = Journal::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/publications', [
        'status' => 'published',
        'title' => 'Test Publication',
        'doi' => '10.1234/1234',
        'is_open_access' => true,
        'journal_id' => $journal->id,
        'accepted_on' => '2021-01-01',
        'published_on' => '2021-03-01',
        'embargoed_until' => '2021-12-31',
    ]);

    $response->assertCreated();
    $response->assertJsonPath('data.title', 'Test Publication');
    $response->assertJsonPath('data.is_open_access', true);
    $response->assertJsonPath('data.manuscript_id', null);
});

/** Test a user can't create a publication with an invalid DOI */
test('a user cannot create a publication with an invalid DOI', function () {
    $user = User::factory()->create();
    $journal = Journal::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/publications', [
        'title' => 'Test Publication',
        'doi' => '10s.1234/1234',
        'is_open_access' => true,
        'journal_id' => $journal->id,
        'accepted_on' => '2021-01-01',
        'published_on' => '2021-03-01',
        'embargoed_until' => '2021-12-31',
    ]);

    ray($response->json());

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('doi');
});

test('a user view a publication via its id', function () {
    $user = User::factory()->create();
    $publication = Publication::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->getJson('/api/publications/'.$publication->id);

    $response->assertOk();
    $response->assertJsonPath('data.id', $publication->id);
});

test('a user can update their publication', function () {
    $user = User::factory()->create();
    $publication = Publication::factory()->create([
        'user_id' => $user->id,
        'status' => PublicationStatus::PUBLISHED,
    ]);

    $response = $this->actingAs($user)->putJson('/api/publications/'.$publication->id, [
        'title' => 'Updated Publication',
        'doi' => '10.1234/1234',
        'is_open_access' => true,
        'accepted_on' => '2021-01-01',
        'published_on' => '2021-03-01',
        'embargoed_until' => '2021-12-31',
    ]);

    $response->assertOk();
    $response->assertJsonPath('data.title', 'Updated Publication');
});

test('a user can update their publication to be published from accepted', function () {
    $user = User::factory()->create();
    $publication = Publication::factory()->create([
        'user_id' => $user->id,
        'status' => PublicationStatus::ACCEPTED,
    ]);

    $response = $this->actingAs($user)->putJson('/api/publications/'.$publication->id, [
        'status' => PublicationStatus::PUBLISHED,
        'published_on' => '2021-03-01',
    ]);

    $response->assertOk();
    $response->assertJsonPath('data.status', PublicationStatus::PUBLISHED->value);
});

test('a user cannot update their publication from published to accepted', function () {
    $user = User::factory()->create();
    $publication = Publication::factory()->create([
        'user_id' => $user->id,
        'status' => PublicationStatus::PUBLISHED,
    ]);

    $response = $this->actingAs($user)->putJson('/api/publications/'.$publication->id, [
        'status' => PublicationStatus::ACCEPTED,
        'published_on' => '',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('status');
});

test('a user cannot update their publication to be published without a published date', function () {
    $user = User::factory()->create();
    $publication = Publication::factory()->create([
        'user_id' => $user->id,
        'status' => PublicationStatus::ACCEPTED,
    ]);

    $response = $this->actingAs($user)->putJson('/api/publications/'.$publication->id, [
        'status' => PublicationStatus::PUBLISHED,
        'published_on' => '',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('published_on');
    expect(Publication::find($publication->id)->status)->toBe(PublicationStatus::ACCEPTED);
});
