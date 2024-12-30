<?php

use App\Enums\ManagementReviewStepStatus;
use App\Enums\ManuscriptRecordStatus;
use App\Enums\ManuscriptRecordType;
use App\Events\ManuscriptRecordWithdrawnByAuthor;
use App\Mail\ManuscriptRecordToReviewMail;
use App\Models\Journal;
use App\Models\ManuscriptAuthor;
use App\Models\ManuscriptRecord;
use App\Models\User;
use Illuminate\Http\UploadedFile;

test('an authenticated user can create a new manuscript', function () {
    $user = User::factory()->create();

    // expected data once created
    $manuscript_data = collect([
        'type' => ManuscriptRecordType::PRIMARY->value,
        'status' => ManuscriptRecordStatus::DRAFT->value,
        'title' => 'My manuscript working title',
        'region_id' => rand(1, 8),
        'user_id' => $user->id,
    ]);

    // data to be sent to the api
    $submit_data = $manuscript_data->only(['title', 'region_id', 'type'])->toArray();

    $this->postJson('/api/manuscript-records', $submit_data)->assertUnauthorized();

    $response = $this->actingAs($user)->postJson('/api/manuscript-records', $submit_data)->assertCreated();

    expect($response->json('data'))->toMatchArray($manuscript_data->toArray());
    expect(ManuscriptRecord::find($response->json('data.id')))->toMatchArray($manuscript_data->toArray());
});

test('an authenticator user can see the manuscript for which they are an author', function () {
    $author = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->has(ManuscriptAuthor::factory()->count(2))->create();
    // add the author to the manuscript
    $manuscript->manuscriptAuthors()->create([
        'author_id' => $author->author->id,
        'is_corresponding_author' => false,
        'organization_id' => $author->author->organization_id,
    ]);

    $response = $this->actingAs($author)->getJson('/api/manuscript-records/'.$manuscript->id)->assertOk();
    expect($response->json('data.id'))->toBe($manuscript->id);
});

test('a user can save a draft manuscript', function () {
    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->putJson("/api/manuscript-records/{$manuscript->id}", [
        'title' => 'My new title',
    ])->assertOk();

    expect($response->json('data.title'))->toBe('My new title');

    // check that the database has been updated
    expect(ManuscriptRecord::find($manuscript->id)->title)->toBe('My new title');

    $data = [
        'title' => 'My new title',
        'region_id' => 1,
        'type' => ManuscriptRecordType::SECONDARY->value,
        'abstract' => 'My new abstract',
        'pls' => 'My new pls',
        'relevant_to' => 'My new relevant_to',
        'potential_public_interest' => true,
        'functional_area_id' => null,
    ];

    // update all the fields
    $response = $this->actingAs($user)->putJson("/api/manuscript-records/{$manuscript->id}", $data)->assertOk();

    // assert new data in response and database
    expect($response->json('data'))->toMatchArray($data);
    expect(ManuscriptRecord::find($manuscript->id))->toMatchArray($data);

    // clean up the files created by the test
    $manuscript->delete();
});

test('a user can attach and download a pdf version of their manuscript to a manuscript record', function () {
    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->create(['user_id' => $user->id]);

    // try to list the pdfs without uploading one - list should be empty
    $response = $this->actingAs($user)->getJson("/api/manuscript-records/{$manuscript->id}/files")->assertOk();
    expect($response->json('data'))->toBeEmpty();

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
    $kbLimit = config('media-library.max_file_size') / 1024;

    $file = UploadedFile::fake()->createWithContent('test.pdf', $fakePdfContent)->size($kbLimit + 100);

    $response = $this->actingAs($user)->postJson("/api/manuscript-records/{$manuscript->id}/files", [
        'pdf' => $file,
    ]);

    // expect response to be 422, file too big
    $response->assertStatus(422);
    expect($response->json('errors.pdf.0'))->toContain('The pdf must not be greater than');

    $file = UploadedFile::fake()->createWithContent('test.pdf', $fakePdfContent)->size($kbLimit);

    $response = $this->actingAs($user)->postJson("/api/manuscript-records/{$manuscript->id}/files", [
        'pdf' => $file,
    ]);

    expect($response->json('data.file_name'))->toBe('test.pdf');
    expect($response->json('data.uuid'))->toBeString();
    expect($response->json('can.download'))->toBe(true);

    // save uuid
    $uuid = $response->json('data.uuid');

    // check that the pdf has been uploaded
    expect($manuscript->getLastManuscriptFile()->file_name)->toBe('test.pdf');

    // download the pdf check file in response
    $response = $this->actingAs($user)->getJson("/api/manuscript-records/{$manuscript->id}/files/{$uuid}?download=true")->assertOk();
    $response->assertDownload('test.pdf');
    // check if we can just get the resource
    $response = $this->actingAs($user)->getJson("/api/manuscript-records/{$manuscript->id}/files/{$uuid}")->assertOk();
    expect($response->json('data.file_name'))->toBe('test.pdf');

    // upload a new pdf - it should be added to the list
    $file = UploadedFile::fake()->createWithContent('test2.pdf', $fakePdfContent)->size(1000);
    $response = $this->actingAs($user)->postJson("/api/manuscript-records/{$manuscript->id}/files", [
        'pdf' => $file,
    ])->assertCreated();
    expect($response->json('data.file_name'))->toBe('test2.pdf');
    $uuid2 = $response->json('data.uuid');
    $response = $this->actingAs($user)->getJson("/api/manuscript-records/{$manuscript->id}/files/{$uuid2}?download=true")->assertOk();
    $response->assertDownload('test2.pdf');

    // get list of files - we should have two files
    $response = $this->actingAs($user)->getJson("/api/manuscript-records/{$manuscript->id}/files")->assertOk();
    expect($response->json('data'))->toHaveCount(2);
});

test('a user cannot submit a manuscript record that does not have all mandatory fields for internal review', function () {
    $manuscript = ManuscriptRecord::factory()->create();

    $reviewerUser = User::factory()->create();

    $data = [
        'reviewer_user_id' => $reviewerUser->id,
    ];

    $response = $this->actingAs($manuscript->user)->putJson("/api/manuscript-records/{$manuscript->id}/submit-for-review", $data)->assertStatus(422);

    expect($response->json('errors'))->toHaveKeys(['abstract', 'manuscript_authors', 'manuscript_pdf']);
});

test('a user cannot submit a filled manuscript record to himself or an author of the manuscript', function () {
    $manuscript = ManuscriptRecord::factory()->filled()->create();

    $author = $manuscript->manuscriptAuthors()->first()->author;
    $authorUser = User::factory()->create(['email' => $author->email]);

    $data = [
        'reviewer_user_id' => $manuscript->user_id,
    ];

    $response = $this->actingAs($manuscript->user)->putJson("/api/manuscript-records/{$manuscript->id}/submit-for-review", $data)->assertStatus(422);

    expect($response->json('errors'))->toHaveKeys(['reviewer_user_id']);

    $data = [
        'reviewer_user_id' => $authorUser->id,
    ];

    $response = $this->actingAs($manuscript->user)->putJson("/api/manuscript-records/{$manuscript->id}/submit-for-review", $data)->assertStatus(422);
});

test('a user can submit a filled manuscript record', function () {
    Mail::fake();

    $radomUser = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->filled()->create();
    $reviewerUser = User::factory()->create();

    $data = [
        'reviewer_user_id' => $reviewerUser->id,
    ];

    // random user cannot submit manuscript
    $this->actingAs($radomUser)->putJson("/api/manuscript-records/{$manuscript->id}/submit-for-review", $data)->assertForbidden();

    $response = $this->actingAs($manuscript->user)->putJson("/api/manuscript-records/{$manuscript->id}/submit-for-review", $data);
    $response->assertOk();

    Mail::assertQueued(ManuscriptRecordToReviewMail::class);

    expect($response->json('data.status'))->toBe(ManuscriptRecordStatus::IN_REVIEW->value);

    // check that the manuscript files has been locked
    expect($manuscript->getLastManuscriptFile()->getCustomProperty('locked'))->toBe(true);
    // try to delete the manuscript file
    $response = $this->actingAs($manuscript->user)->deleteJson("/api/manuscript-records/{$manuscript->id}/files/{$manuscript->getLastManuscriptFile()->uuid}")->assertForbidden();

    // check that a management review step has been created and for it belongs to the reviewer
    expect($manuscript->managementReviewSteps()->count())->toBe(1);
    expect($manuscript->managementReviewSteps()->first()->user_id)->toBe($reviewerUser->id);
    expect($manuscript->managementReviewSteps()->first()->status)->toBe(ManagementReviewStepStatus::PENDING);
});

test('a user can withdraw a manuscript record', function () {
    Event::fake();

    $manuscript = ManuscriptRecord::factory()->reviewed()->create();

    // check they cannot withdraw if they are not the author
    $this->actingAs(User::factory()->create())->putJson("/api/manuscript-records/{$manuscript->id}/withdraw")->assertForbidden();

    $response = $this->actingAs($manuscript->user)->putJson("/api/manuscript-records/{$manuscript->id}/withdraw")->assertOk();

    Event::assertDispatched(ManuscriptRecordWithdrawnByAuthor::class);

    expect($response->json('data.status'))->toBe(ManuscriptRecordStatus::WITHDRAWN->value);
});

test('a user cannot withdraw a manuscript record that was accepted', function () {
    $manuscript = ManuscriptRecord::factory()->filled()->create();
    $manuscript->status = ManuscriptRecordStatus::ACCEPTED;
    $manuscript->save();

    $this->actingAs($manuscript->user)->putJson("/api/manuscript-records/{$manuscript->id}/withdraw")->assertForbidden();
});

test('a user can mark their manuscript as submitted', function () {
    Event::fake();

    // manuscript must be reviewed
    $manuscript = ManuscriptRecord::factory()->filled()->create([
        'status' => ManuscriptRecordStatus::REVIEWED,
    ]);

    $data = [
        'submitted_to_journal_on' => now()->toDateTimeString(),
    ];

    // only the author can mark the manuscript as submitted
    $this->actingAs(User::factory()->create())->putJson("/api/manuscript-records/{$manuscript->id}/submitted", $data)->assertForbidden();

    $this->actingAs($manuscript->user)->putJson("/api/manuscript-records/{$manuscript->id}/submitted", $data)->assertOk();

    expect($manuscript->fresh()->status)->toBe(ManuscriptRecordStatus::SUBMITTED);
});

test('a user cannot submit their manuscript for review if they do not want an OGL but have provide no explanation', function () {
    $manuscript = ManuscriptRecord::factory()->filled()->create([
        'apply_ogl' => false,
    ]);

    $this->actingAs($manuscript->user)->putJson("/api/manuscript-records/{$manuscript->id}/submit-for-review", [
        'reviewer_user_id' => User::factory()->create()->id,
    ])->assertStatus(422);

    $manuscript->no_ogl_explanation = 'I do not want to apply for OGL';
    $manuscript->save();

    $this->actingAs($manuscript->user)->putJson("/api/manuscript-records/{$manuscript->id}/submit-for-review", [
        'reviewer_user_id' => User::factory()->create()->id,
    ])->assertOk();
});

test('a user can mark their manuscript as accepted', function () {
    // create a manuscript that has been submitted
    $manuscript = ManuscriptRecord::factory()->filled()->create([
        'status' => ManuscriptRecordStatus::SUBMITTED,
    ]);

    $data = [
        'submitted_to_journal_on' => now()->subMonth()->toDateTimeString(),
        'accepted_on' => now()->toDateTimeString(),
        'journal_id' => Journal::factory()->create()->id,
    ];

    // only the author can mark the manuscript as accepted
    $this->actingAs(User::factory()->create())->putJson("/api/manuscript-records/{$manuscript->id}/accepted", $data)->assertForbidden();

    $this->actingAs($manuscript->user)->putJson("/api/manuscript-records/{$manuscript->id}/accepted", $data)->assertOk();

    expect($manuscript->fresh()->status)->toBe(ManuscriptRecordStatus::ACCEPTED);
    expect($manuscript->publication->manuscript_record_id)->toBe($manuscript->id);
});

test('a user can delete their manuscript record if it is a draft', function () {
    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->filled()->create(['user_id' => $user->id]);

    // check that the resource returned allows the user to delete the manuscript
    $response = $this->actingAs($user)->getJson("/api/manuscript-records/{$manuscript->id}")->assertOk();
    expect($response->json('can.delete'))->toBe(true);

    // check that user cannot delete the manuscript if it is not a draft
    $manuscript->status = ManuscriptRecordStatus::SUBMITTED;
    $manuscript->save();
    $response = $this->actingAs($user)->getJson("/api/manuscript-records/{$manuscript->id}")->assertOk();
    expect($response->json('can.delete'))->toBe(false);

    $manuscript->status = ManuscriptRecordStatus::DRAFT;
    $manuscript->save();

    // delete the manuscript
    $response = $this->actingAs($user)->deleteJson("/api/manuscript-records/{$manuscript->id}")->assertNoContent();

    // check that the manuscript has been deleted
    expect(ManuscriptRecord::find($manuscript->id))->toBeNull();

    // try again with submitted manuscript
    $manuscript = ManuscriptRecord::factory()->in_review()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->getJson("/api/manuscript-records/{$manuscript->id}")->assertOk();
    expect($response->json('can.delete'))->toBe(false);

    // delete the manuscript
    $response = $this->actingAs($user)->deleteJson("/api/manuscript-records/{$manuscript->id}")->assertForbidden();

    // check that the manuscript has not been deleted
    expect(ManuscriptRecord::find($manuscript->id))->not->toBeNull();
});

test('an authenticated user can get manuscript metadata', function () {
    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->create();

    $response = $this->actingAs($user)->getJson("/api/manuscript-records/{$manuscript->id}/metadata")->assertOk();

    expect($response->json('data.id'))->toBe($manuscript->id);
    expect($response->json('data.ulid'))->toBe($manuscript->ulid->toString());
    expect($response->json('can.view'))->toBe(false);
    expect($response->json('can.update'))->toBe(false);
    expect($response->json('can.delete'))->toBe(false);
});
