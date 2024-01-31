<?php

use App\Enums\PublicationStatus;
use App\Models\Journal;
use App\Models\Publication;
use App\Models\User;
use Illuminate\Http\UploadedFile;

/** Test that a user can query publications */
test('a user can get a list of publications', function () {
    $user = User::factory()->create();
    $publications = Publication::factory()->count(5)->published()->create();
    // can't see unpublished publications
    Publication::factory()->create();

    $response = $this->actingAs($user)->getJson('/api/publications');

    ray($response->json());

    $response->assertOk();
    $response->assertJsonCount(5, 'data');
});

test('a user can get a list of publications with a filter', function () {
    $user = User::factory()->create();
    $publications = Publication::factory()->count(5)->published()->create();
    $openAccessPublication = Publication::factory()->published()->openAccess()->create();

    $response = $this->actingAs($user)->getJson('/api/publications?filter[open_access]=1');

    $response->assertOk();
    $response->assertJsonCount(1, 'data');
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

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('doi');
});

test('a user view a publication via its id', function () {
    $user = User::factory()->create();
    $publication = Publication::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->getJson('/api/publications/' . $publication->id);

    $response->assertOk();
    $response->assertJsonPath('data.id', $publication->id);
});

test('a user can update their publication', function () {
    $user = User::factory()->create();
    $publication = Publication::factory()->create([
        'user_id' => $user->id,
        'status' => PublicationStatus::PUBLISHED,
    ]);

    $response = $this->actingAs($user)->putJson('/api/publications/' . $publication->id, [
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

    $response = $this->actingAs($user)->putJson('/api/publications/' . $publication->id, [
        'status' => PublicationStatus::PUBLISHED,
        'published_on' => $publication->accepted_on->subDay()->format('Y-m-d'),
    ]);

    expect($response->status())->toBe(422);

    $response = $this->actingAs($user)->putJson('/api/publications/' . $publication->id, [
        'status' => PublicationStatus::PUBLISHED,
        'published_on' => $publication->accepted_on->addDays(15)->format('Y-m-d'),
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

    $response = $this->actingAs($user)->putJson('/api/publications/' . $publication->id, [
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

    $response = $this->actingAs($user)->putJson('/api/publications/' . $publication->id, [
        'status' => PublicationStatus::PUBLISHED,
        'published_on' => '',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('published_on');
    expect(Publication::find($publication->id)->status)->toBe(PublicationStatus::ACCEPTED);
});

test('a user can attach a new publication pdf to their publication', function () {
    $user = User::factory()->create();
    $publication = Publication::factory()->create([
        'user_id' => $user->id,
        'status' => PublicationStatus::PUBLISHED,
    ]);

    // fake pdf
    $fakePdfContent = <<<'PDF'
        %PDF-1.4
        1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj
        2 0 obj<</Type/Pages/Count 1/Kids[3 0 R]>>endobj
        3 0 obj<</Type/Page/MediaBox[0 0 612 792]/Parent 2 0 R/Resources<<>>>>endobj
        xref
        0 4
        0000000000 65535 f
        0000000009 00000 n
        0000000052 00000 n
        0000000101 00000 n
        trailer<</Size 4/Root 1 0 R>>
        startxref
        178
        %%EOF
        PDF;

    // upload pdf
    $file = UploadedFile::fake()->createWithContent('test.pdf', $fakePdfContent)->size(1000);

    $response = $this->actingAs($user)->postJson('/api/publications/' . $publication->id . '/pdf', [
        'pdf' => $file,
    ]);

    $response->assertOk();
    //expect($response->json('data.publication_pdf'))->not()->toBeNull();

    // check that user can see the pdf resrouce
    $response = $this->actingAs($user)->get('/api/publications/' . $publication->id . '/pdf');
    $response->assertOk();
    ray($response->json());
    expect($response->json('data.file_name'))->toBe('test.pdf');

    // check that user can download the pdf
    $response = $this->actingAs($user)->get('/api/publications/' . $publication->id . '/pdf');
    $response->assertOk();
});

test('a regular user can only view a publication if it has been published', function () {

    $user = User::factory()->create();
    $publication = Publication::factory()->withManuscript()->create();
    $response = $this->actingAs($user)->getJson('/api/publications/' . $publication->id);
    $response->assertForbidden();

    $publication = Publication::factory()->published()->create();
    $response = $this->actingAs($user)->getJson('/api/publications/' . $publication->id);
    $response->assertOk();
});

test('a user that can see the manuscript can see the publication', function () {

    $publication = Publication::factory()->withManuscript()->create();
    // get a user that did the review
    $user = $publication->manuscriptRecord->managementReviewSteps->first()->user;
    ray($user);
    $response = $this->actingAs($user)->getJson('/api/publications/' . $publication->id);
    $response->assertOk();
});
