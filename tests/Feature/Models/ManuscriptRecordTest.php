<?php

use App\Enums\ManuscriptRecordStatus;
use App\Enums\ManuscriptRecordType;
use App\Mail\ManuscriptRecordToReviewMail;
use App\Models\ManuscriptAuthor;
use App\Models\ManuscriptRecord;
use App\Models\User;
use Illuminate\Http\UploadedFile;

test('an authenticated user can create a new manuscript', function () {
    $this->seed();

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

test('an authenticated users can get a list of their manuscripts', function () {
    $this->seed();

    $manuscripts = ManuscriptRecord::factory()->count(5)->create();
    $user = User::factory()->create();
    $manuscripts = ManuscriptRecord::factory()->has(ManuscriptAuthor::factory()->count(2))->count(5)->count(5)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->getJson('/api/my/manuscript-records')->assertOk();

    expect($response->json('data'))->toHaveCount(5);
    // expect manuscript author to be included
    expect($response->json('data.0.data.manuscript_authors'))->toHaveCount(2);
});

test('a user can save a draft manuscript', function () {
    $this->seed();

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
        'scientific_implications' => 'My new scientific_implications',
        'regions_and_species' => 'My new regions_and_species',
        'relevant_to' => 'My new relevant_to',
        'additional_information' => 'My new additional_information',
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
    $this->seed();

    $user = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->create(['user_id' => $user->id]);

    // try to download a pdf without uploading one
    $this->actingAs($user)->getJson("/api/manuscript-records/{$manuscript->id}/pdf")->assertNotFound();

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

    $response = $this->actingAs($user)->postJson("/api/manuscript-records/{$manuscript->id}/pdf", [
        'pdf' => $file,
    ])->assertOk();

    // check that the pdf has been uploaded
    expect($manuscript->getManuscriptFile()->file_name)->toBe('test.pdf');

    // download the pdf check file in response
    $response = $this->actingAs($user)->getJson("/api/manuscript-records/{$manuscript->id}/pdf")->assertOk();
    $response->assertDownload('test.pdf');

    // upload a new pdf - it should replace the old one
    $file = UploadedFile::fake()->createWithContent('test2.pdf', $fakePdfContent)->size(1000);
    $response = $this->actingAs($user)->postJson("/api/manuscript-records/{$manuscript->id}/pdf", [
        'pdf' => $file,
    ])->assertOk();
    $response = $this->actingAs($user)->getJson("/api/manuscript-records/{$manuscript->id}/pdf")->assertOk();
    $response->assertDownload('test2.pdf');
});

test('a user cannot submit a manuscript record that does not have all mandatory fields for internal review', function () {
    $this->seed();

    $manuscript = ManuscriptRecord::factory()->create();

    $response = $this->actingAs($manuscript->user)->putJson("/api/manuscript-records/{$manuscript->id}/submit-for-review")->assertStatus(422);

    expect($response->json('errors'))->toHaveKeys(['abstract', 'manuscript_authors', 'manuscript_pdf']);
});

test('a user can submit a filled manuscript record', function () {
    $this->seed();

    Mail::fake();

    $radomUser = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->filled()->create();

    // random user cannot submit manuscript
    $this->actingAs($radomUser)->putJson("/api/manuscript-records/{$manuscript->id}/submit-for-review")->assertForbidden();

    $response = $this->actingAs($manuscript->user)->putJson("/api/manuscript-records/{$manuscript->id}/submit-for-review")->assertOk();

    Mail::assertSent(ManuscriptRecordToReviewMail::class, function ($mail) use ($manuscript) {
        ray()->mailable($mail);

        return $mail->hasCc($manuscript->user->email);
    });

    expect($response->json('data.status'))->toBe(ManuscriptRecordStatus::IN_REVIEW->value);
});
