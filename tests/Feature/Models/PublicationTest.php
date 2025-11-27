<?php

use App\Enums\PublicationStatus;
use App\Enums\SupplementaryFileType;
use App\Models\Journal;
use App\Models\Publication;
use App\Models\User;
use Illuminate\Http\UploadedFile;

/** Test that a user can query publications */
test('a user can get a list of publications', function (): void {
    $user = User::factory()->create();
    $publications = Publication::factory()->count(5)->published()->create();
    // can't see unpublished publications
    Publication::factory()->create();

    $response = $this->actingAs($user)->getJson('/api/publications');

    ray($response->json());

    $response->assertOk();
    $response->assertJsonCount(5, 'data');
});

test('a user can get a list of publications with a filter', function (): void {
    $user = User::factory()->create();
    $publications = Publication::factory()->count(5)->published()->create();
    $openAccessPublication = Publication::factory()->published()->openAccess()->create();

    $response = $this->actingAs($user)->getJson('/api/publications?filter[open_access]=1');

    $response->assertOk();
    $response->assertJsonCount(1, 'data');
    expect($response->json('data.0.data.id'))->toBe($openAccessPublication->id);
});

/** Test that a user can get a list of their publications */
test('a user can get a list of their publications', function (): void {
    $user = User::factory()->create();
    $publications = Publication::factory()->count(5)->create(['user_id' => $user->id]);
    Publication::factory()->count(5)->create();

    $response = $this->actingAs($user)->getJson('/api/my/publications');

    $response->assertOk();
    $response->assertJsonCount(5, 'data');
});

/** Test that a user can create a new publication without a manuscript attached */
test('a user can create a new publication without a manuscript attached', function (): void {
    $user = User::factory()->create();
    $journal = Journal::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/publications', [
        'status' => 'published',
        'title' => 'Test Publication',
        'doi' => 'https://doi.org/10.1234/1234',
        'is_open_access' => true,
        'journal_id' => $journal->id,
        'accepted_on' => '2021-01-01',
        'published_on' => '2021-03-01',
        'embargoed_until' => '2021-12-31',
        'region_id' => 1,
    ]);

    $response->assertCreated();
    $response->assertJsonPath('data.title', 'Test Publication');
    $response->assertJsonPath('data.is_open_access', true);
    $response->assertJsonPath('data.manuscript_id', null);
});

test('a user can create an accepted publication without a published date', function (): void {
    $user = User::factory()->create();
    $journal = Journal::factory()->create();

    $values = [
        'status' => PublicationStatus::PUBLISHED,
        'title' => 'Test Publication',
        'doi' => 'https://doi.org/10.1234/1234',
        'is_open_access' => true,
        'journal_id' => $journal->id,
        'accepted_on' => '',
        'published_on' => '',
        'region_id' => 1,
    ];

    // no published date but published status - should fail
    $response = $this->actingAs($user)->postJson('/api/publications', $values);
    $response->assertStatus(422);

    // change status - should pass
    $values['status'] = PublicationStatus::ACCEPTED;
    $response = $this->actingAs($user)->postJson('/api/publications', $values);

    $response->assertCreated();
    $response->assertJsonPath('data.title', 'Test Publication');
    $response->assertJsonPath('data.is_open_access', true);
    $response->assertJsonPath('data.manuscript_id', null);
});

/** Test a user can't create a publication with an invalid DOI */
test('a user cannot create a publication with an invalid DOI', function (): void {
    $user = User::factory()->create();
    $journal = Journal::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/publications', [
        'title' => 'Test Publication',
        'doi' => 'https://doi.org/10s.1234/1234',
        'is_open_access' => true,
        'journal_id' => $journal->id,
        'accepted_on' => '2021-01-01',
        'published_on' => '2021-03-01',
        'embargoed_until' => '2021-12-31',
        'region_id' => 1,
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('doi');
});

test('a user view a publication via its id', function (): void {
    $user = User::factory()->create();
    $publication = Publication::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->getJson('/api/publications/'.$publication->id);

    $response->assertOk();
    $response->assertJsonPath('data.id', $publication->id);
});

test('a user can update their publication', function (): void {
    $user = User::factory()->create();
    $publication = Publication::factory()->create([
        'user_id' => $user->id,
        'status' => PublicationStatus::PUBLISHED,
    ]);

    $response = $this->actingAs($user)->putJson('/api/publications/'.$publication->id, [
        'title' => 'Updated Publication',
        'doi' => 'https://doi.org/10.1234/1234',
        'is_open_access' => true,
        'accepted_on' => '2021-01-01',
        'published_on' => '2021-03-01',
        'embargoed_until' => '2021-12-31',
        'region_id' => 1,
    ]);

    $response->assertOk();
    $response->assertJsonPath('data.title', 'Updated Publication');
    $response->assertJsonPath('data.region_id', 1);
});

test('a user can update their publication to be published from accepted', function (): void {
    $user = User::factory()->create();
    $publication = Publication::factory()->create([
        'user_id' => $user->id,
        'status' => PublicationStatus::ACCEPTED,
    ]);

    $response = $this->actingAs($user)->putJson('/api/publications/'.$publication->id, [
        'status' => PublicationStatus::PUBLISHED,
        'published_on' => $publication->accepted_on->subDay()->format('Y-m-d'),
    ]);

    expect($response->status())->toBe(422);

    $response = $this->actingAs($user)->putJson('/api/publications/'.$publication->id, [
        'status' => PublicationStatus::PUBLISHED,
        'published_on' => $publication->accepted_on->addDays(15)->format('Y-m-d'),
    ]);

    $response->assertOk();
    $response->assertJsonPath('data.status', PublicationStatus::PUBLISHED->value);
});

test('a user cannot update their publication from published to accepted', function (): void {
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

test('a user cannot update their publication to be published without a published date', function (): void {
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

test('a user can attach a new publication pdf to their publication', function (): void {
    $user = User::factory()->create();
    $publication = Publication::factory()->create([
        'user_id' => $user->id,
        'status' => PublicationStatus::PUBLISHED,
    ]);

    // fake pdf
    $fakePdfContent = <<<'PDF_WRAP'
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
    PDF_WRAP;

    // upload pdf
    $file = UploadedFile::fake()->createWithContent('test.pdf', $fakePdfContent)->size(1000);

    $response = $this->actingAs($user)->postJson('/api/publications/'.$publication->id.'/files', [
        'pdf' => $file,
    ]);

    $response->assertCreated();
    expect($response->json('data.file_name'))->toBe('test.pdf');
    expect($response->json('data.uuid'))->toBeString();
    // expect($response->json('data.publication_pdf'))->not()->toBeNull();

    $uuid = $response->json('data.uuid');

    // check that user can see the pdf resrouce
    $response = $this->actingAs($user)->get("/api/publications/{$publication->id}/files/{$uuid}");
    $response->assertOk();
    expect($response->json('data.file_name'))->toBe('test.pdf');

    // check that user can download the pdf
    $response = $this->actingAs($user)->get("/api/publications/{$publication->id}/files/{$uuid}?download=true");
    $response->assertDownload('test.pdf');

    // can add another pdf
    $file = UploadedFile::fake()->createWithContent('test2.pdf', $fakePdfContent)->size(1000);
    $response = $this->actingAs($user)->postJson('/api/publications/'.$publication->id.'/files', [
        'pdf' => $file,
    ]);

    $response->assertCreated();
    expect($response->json('data.file_name'))->toBe('test2.pdf');

    // check that user can list the pdfs
    $response = $this->actingAs($user)->get("/api/publications/{$publication->id}/files");
    $response->assertOk();
    $response->assertJsonCount(2, 'data');
});

test('a regular user can only view a publication if it has been published', function (): void {

    $user = User::factory()->create();
    $publication = Publication::factory()->withManuscript()->create();
    $response = $this->actingAs($user)->getJson('/api/publications/'.$publication->id);
    $response->assertForbidden();

    $publication = Publication::factory()->published()->create();
    $response = $this->actingAs($user)->getJson('/api/publications/'.$publication->id);
    $response->assertOk();
});

test('a user that can see the manuscript can see the publication', function (): void {

    $publication = Publication::factory()->withManuscript()->create();

    $publication->load('manuscriptRecord.managementReviewSteps.user');    // get a user that did the review
    $user = $publication->manuscriptRecord->managementReviewSteps->first()->user;
    $response = $this->actingAs($user)->getJson('/api/publications/'.$publication->id);
    $response->assertOk();
});

test('a user can manage supplementary files for their publication record', function (): void {
    $publication = Publication::factory()->create();
    $user = $publication->user;

    $fakePdfContent = <<<'PDF_WRAP'
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
    PDF_WRAP;

    // upload pdf
    $file = UploadedFile::fake()->createWithContent('test.pdf', $fakePdfContent)->size(1000);

    // can't do it if wrong user
    $response = $this->actingAs(User::factory()->create())->postJson('/api/publications/'.$publication->id.'/supplementary-files', [
        'pdf' => $file,
        'supplementary_file_type' => SupplementaryFileType::MANUSCRIPT_RECORD_FORM->value,
        'description' => 'Test Description',
    ]);

    $response->assertForbidden();

    $response = $this->actingAs($user)->postJson('/api/publications/'.$publication->id.'/supplementary-files', [
        'file' => $file,
        'supplementary_file_type' => SupplementaryFileType::MANUSCRIPT_RECORD_FORM->value,
        'description' => 'Test Description',
    ]);

    $response->assertCreated();
    $uuid = $response->json('data.uuid');

    // list the files
    $response = $this->actingAs($user)->getJson('/api/publications/'.$publication->id.'/supplementary-files');
    $response->assertOk();
    $response->assertJsonCount(1, 'data');

    $response2 = $this->actingAs($user)->get("/api/publications/{$publication->id}/supplementary-files/{$uuid}?download=true");
    $response2->assertDownload('test.pdf');

    // can't delete if wrong user
    $response = $this->actingAs(User::factory()->create())->deleteJson("/api/publications/{$publication->id}/supplementary-files/{$uuid}");
    $response->assertForbidden();

    // delete the file
    $response = $this->actingAs($user)->deleteJson("/api/publications/{$publication->id}/supplementary-files/{$uuid}");
    $response->assertNoContent();
});

test('a user can upload a word document as a supplementary files', function (): void {
    $publication = Publication::factory()->create();
    $user = $publication->user;

    // upload word document
    $file = UploadedFile::fake()->createWithContent('test.docx', file_get_contents(__DIR__.'/support_files/test_doc.docx'))->size(1000);

    $response = $this->actingAs($user)->postJson('/api/publications/'.$publication->id.'/supplementary-files', [
        'file' => $file,
        'supplementary_file_type' => SupplementaryFileType::MANUSCRIPT_RECORD_FORM->value,
        'description' => 'Test Description',
    ]);

    $response->assertCreated();
    $uuid = $response->json('data.uuid');

    // list the files
    $response = $this->actingAs($user)->getJson('/api/publications/'.$publication->id.'/supplementary-files');
    $response->assertOk();
    $response->assertJsonCount(1, 'data');

    $response2 = $this->actingAs($user)->get("/api/publications/{$publication->id}/supplementary-files/{$uuid}?download=true");
    $response2->assertDownload('test.docx');

    // delete the file
    $response = $this->actingAs($user)->deleteJson("/api/publications/{$publication->id}/supplementary-files/{$uuid}");
    $response->assertNoContent();
});

test('an editor can edit any publication', function (): void {
    $publication = Publication::factory()->create();
    $editor = User::factory()->editor()->create();

    $response = $this->actingAs($editor)->putJson('/api/publications/'.$publication->id, [
        'title' => 'Updated Publication',
        'doi' => 'https://doi.org/10.1234/1234',
        'is_open_access' => true,
        'accepted_on' => '2021-01-01',
        'published_on' => '2021-03-01',
        'embargoed_until' => '2021-12-31',
        'region_id' => 1,
    ]);

    $response->assertOk();
    $response->assertJsonPath('data.title', 'Updated Publication');
    $response->assertJsonPath('data.region_id', 1);
});

test("an editor can view and download a publication's files", function (): void {
    $publication = Publication::factory()->published()->create();
    $editor = User::factory()->editor()->create();

    $response = $this->actingAs($editor)->getJson('/api/publications/'.$publication->id.'/supplementary-files');
    $response->assertOk();
    $uuid = $response->json('data.0.data.uuid');
    $filename = $response->json('data.0.data.file_name');

    $response = $this->actingAs($editor)->get("/api/publications/{$publication->id}/supplementary-files/{$uuid}?download=true");
    $response->assertDownload($filename);

    $response = $this->actingAs($editor)->getJson('/api/publications/'.$publication->id.'/files');
    $response->assertOk();
    $uuid = $response->json('data.0.data.uuid');
    $filename = $response->json('data.0.data.file_name');

    $response = $this->actingAs($editor)->get("/api/publications/{$publication->id}/files/{$uuid}?download=true");
    $response->assertDownload($filename);

});

test('only a chief editor can publish a secondary publication', function (): void {
    $chiefEditor = User::factory()->chiefEditor()->create();

    $publication = Publication::factory()->dfoSeries()->create();
    $pubUser = $publication->user;

    // try with user, it should be forbidden
    $response = $this->actingAs($pubUser)->putJson('/api/publications/'.$publication->id, [
        'status' => PublicationStatus::PUBLISHED,
        'published_on' => now()->format('Y-m-d'),
    ]);
    $response->assertForbidden();

    $response = $this->actingAs($chiefEditor)->putJson('/api/publications/'.$publication->id, [
        'status' => PublicationStatus::PUBLISHED,
        'published_on' => now()->format('Y-m-d'),
    ]);

    $response->assertOk();
    expect($publication->fresh()->status)->toBe(PublicationStatus::PUBLISHED);
});
