<?php

use App\Enums\ManagementReviewStepDecision;
use App\Enums\ManagementReviewStepStatus;
use App\Enums\ManuscriptRecordStatus;
use App\Models\Author;
use App\Models\ManagementReviewStep;
use App\Models\ManuscriptAuthor;
use App\Models\ManuscriptRecord;
use App\Models\Shareable;
use App\Models\User;

test('manuscript author can edit when manuscript is flagged for follow-up', function (): void {
    $authorUser = User::factory()->create();
    $author = Author::factory()->create(['user_id' => $authorUser->id]);

    $manuscript = ManuscriptRecord::factory()->filled()->create([
        'status' => ManuscriptRecordStatus::IN_REVIEW,
    ]);

    $manuscript->manuscriptAuthors()->save(
        ManuscriptAuthor::factory()->make(['author_id' => $author->id])
    );

    // Create a completed review step with revision decision (flagged for follow-up)
    ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'status' => ManagementReviewStepStatus::COMPLETED,
        'decision' => ManagementReviewStepDecision::REVISION,
        'completed_at' => now(),
    ]);

    $this->actingAs($authorUser)
        ->putJson("/api/manuscript-records/{$manuscript->id}", [
            'title' => 'Updated by author during revision',
        ])
        ->assertOk();

    expect($manuscript->fresh()->title)->toBe('Updated by author during revision');
});

test('manuscript author cannot edit when manuscript is in review without a revision flag', function (): void {
    $authorUser = User::factory()->create();
    $author = Author::factory()->create(['user_id' => $authorUser->id]);

    $manuscript = ManuscriptRecord::factory()->filled()->create([
        'status' => ManuscriptRecordStatus::IN_REVIEW,
    ]);

    $manuscript->manuscriptAuthors()->save(
        ManuscriptAuthor::factory()->make(['author_id' => $author->id])
    );

    // Create a pending review step with no revision decision
    ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'status' => ManagementReviewStepStatus::PENDING,
        'decision' => ManagementReviewStepDecision::NONE,
    ]);

    $this->actingAs($authorUser)
        ->putJson("/api/manuscript-records/{$manuscript->id}", [
            'title' => 'Should not be allowed',
        ])
        ->assertForbidden();
});

test('proponent can edit in review when they are in the review steps', function (): void {
    $manuscript = ManuscriptRecord::factory()->filled()->create([
        'status' => ManuscriptRecordStatus::IN_REVIEW,
    ]);

    $proponent = User::query()->find($manuscript->user_id);

    // Proponent is added to the review queue (as happens after a revision request)
    ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'user_id' => $proponent->id,
        'status' => ManagementReviewStepStatus::PENDING,
        'decision' => ManagementReviewStepDecision::NONE,
    ]);

    $this->actingAs($proponent)
        ->putJson("/api/manuscript-records/{$manuscript->id}", [
            'title' => 'Updated by proponent during review',
        ])
        ->assertOk();

    expect($manuscript->fresh()->title)->toBe('Updated by proponent during review');
});

test('manuscript author cannot edit after proponent has responded to a revision', function (): void {
    $authorUser = User::factory()->create();
    $author = Author::factory()->create(['user_id' => $authorUser->id]);

    $manuscript = ManuscriptRecord::factory()->filled()->create([
        'status' => ManuscriptRecordStatus::IN_REVIEW,
    ]);

    $manuscript->manuscriptAuthors()->save(
        ManuscriptAuthor::factory()->make(['author_id' => $author->id])
    );

    // Manager flags with revision
    $managerStep = ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'status' => ManagementReviewStepStatus::COMPLETED,
        'decision' => ManagementReviewStepDecision::REVISION,
        'completed_at' => now()->subMinutes(10),
    ]);

    // Proponent responds — completed with NONE decision
    ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'user_id' => $manuscript->user_id,
        'previous_step_id' => $managerStep->id,
        'status' => ManagementReviewStepStatus::COMPLETED,
        'decision' => ManagementReviewStepDecision::NONE,
        'completed_at' => now(),
    ]);

    $this->actingAs($authorUser)
        ->putJson("/api/manuscript-records/{$manuscript->id}", [
            'title' => 'Should not be allowed',
        ])
        ->assertForbidden();
});

test('shared user with edit rights can edit when manuscript is flagged for follow-up', function (): void {
    $manuscript = ManuscriptRecord::factory()->filled()->create([
        'status' => ManuscriptRecordStatus::IN_REVIEW,
    ]);

    $shareable = Shareable::factory()->editable()->create([
        'shareable_id' => $manuscript->id,
        'shareable_type' => ManuscriptRecord::class,
        'shared_by' => $manuscript->user_id,
    ]);

    // Create a completed review step with revision decision
    ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'status' => ManagementReviewStepStatus::COMPLETED,
        'decision' => ManagementReviewStepDecision::REVISION,
        'completed_at' => now(),
    ]);

    $this->actingAs($shareable->user)
        ->putJson("/api/manuscript-records/{$manuscript->id}", [
            'title' => 'Updated by shared user during revision',
        ])
        ->assertOk();

    expect($manuscript->fresh()->title)->toBe('Updated by shared user during revision');
});

test('shared user with edit rights cannot edit when manuscript is in review without revision', function (): void {
    $manuscript = ManuscriptRecord::factory()->filled()->create([
        'status' => ManuscriptRecordStatus::IN_REVIEW,
    ]);

    $shareable = Shareable::factory()->editable()->create([
        'shareable_id' => $manuscript->id,
        'shareable_type' => ManuscriptRecord::class,
        'shared_by' => $manuscript->user_id,
    ]);

    // Only a pending review step, no revision
    ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'status' => ManagementReviewStepStatus::PENDING,
        'decision' => ManagementReviewStepDecision::NONE,
    ]);

    $this->actingAs($shareable->user)
        ->putJson("/api/manuscript-records/{$manuscript->id}", [
            'title' => 'Should not be allowed',
        ])
        ->assertForbidden();
});

test('manuscript author cannot edit when last completed step is not a revision', function (): void {
    $authorUser = User::factory()->create();
    $author = Author::factory()->create(['user_id' => $authorUser->id]);

    $manuscript = ManuscriptRecord::factory()->filled()->create([
        'status' => ManuscriptRecordStatus::IN_REVIEW,
    ]);

    $manuscript->manuscriptAuthors()->save(
        ManuscriptAuthor::factory()->make(['author_id' => $author->id])
    );

    // Create a completed review step with complete decision (not revision)
    ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'status' => ManagementReviewStepStatus::COMPLETED,
        'decision' => ManagementReviewStepDecision::COMPLETE,
        'completed_at' => now(),
    ]);

    $this->actingAs($authorUser)
        ->putJson("/api/manuscript-records/{$manuscript->id}", [
            'title' => 'Should not be allowed',
        ])
        ->assertForbidden();
});
