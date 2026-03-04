<?php

use App\Enums\ManagementReviewStepStatus;
use App\Models\ManagementReviewStep;
use App\Models\ManuscriptRecord;
use App\Models\Region;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

test('migration adds enforce_secondary_review_deadline column with correct default', function (): void {
    $region = Region::first();
    expect($region->enforce_secondary_review_deadline)->toBeFalse();
});

test('secondary manuscript gets deadline when region has enforcement enabled', function (): void {
    Mail::fake();

    $region = Region::first();
    $region->enforce_secondary_review_deadline = true;
    $region->save();

    $manuscript = ManuscriptRecord::factory()->secondary()->filled()->create([
        'region_id' => $region->id,
    ]);
    $reviewer = User::factory()->create();

    $this->actingAs($manuscript->user)->putJson("/api/manuscript-records/{$manuscript->id}/submit-for-review", [
        'reviewer_user_id' => $reviewer->id,
    ])->assertOk();

    $step = $manuscript->managementReviewSteps()->first();
    expect($step->decision_expected_by)->not->toBeNull();
});

test('secondary manuscript gets no deadline when region does not have enforcement enabled', function (): void {
    Mail::fake();

    $region = Region::first();
    $region->enforce_secondary_review_deadline = false;
    $region->save();

    $manuscript = ManuscriptRecord::factory()->secondary()->filled()->create([
        'region_id' => $region->id,
    ]);
    $reviewer = User::factory()->create();

    $this->actingAs($manuscript->user)->putJson("/api/manuscript-records/{$manuscript->id}/submit-for-review", [
        'reviewer_user_id' => $reviewer->id,
    ])->assertOk();

    $step = $manuscript->managementReviewSteps()->first();
    expect($step->decision_expected_by)->toBeNull();
});

test('reassign sets deadline for secondary manuscript when region has enforcement enabled', function (): void {
    Mail::fake();

    $region = Region::first();
    $region->enforce_secondary_review_deadline = true;
    $region->save();

    $reviewer = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->secondary()->in_review(secondary: true)->create([
        'region_id' => $region->id,
    ]);

    $step = ManagementReviewStep::factory()->for($reviewer)->create([
        'manuscript_record_id' => $manuscript->id,
        'comments' => 'reassigning this',
    ]);

    $reviewer2 = User::factory()->create();

    $this->actingAs($reviewer)->putJson("/api/manuscript-records/{$manuscript->id}/management-review-steps/{$step->id}/reassign", [
        'next_user_id' => $reviewer2->id,
    ])->assertOk();

    $nextStep = $manuscript->managementReviewSteps()->where('user_id', $reviewer2->id)->first();
    expect($nextStep->decision_expected_by)->not->toBeNull();
});

test('reassign does not set deadline for secondary manuscript when region has enforcement disabled', function (): void {
    Mail::fake();

    $region = Region::first();
    $region->enforce_secondary_review_deadline = false;
    $region->save();

    $reviewer = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->secondary()->in_review(secondary: true)->create([
        'region_id' => $region->id,
    ]);

    $step = ManagementReviewStep::factory()->for($reviewer)->create([
        'manuscript_record_id' => $manuscript->id,
        'comments' => 'reassigning this',
    ]);

    $reviewer2 = User::factory()->create();

    $this->actingAs($reviewer)->putJson("/api/manuscript-records/{$manuscript->id}/management-review-steps/{$step->id}/reassign", [
        'next_user_id' => $reviewer2->id,
    ])->assertOk();

    $nextStep = $manuscript->managementReviewSteps()->where('user_id', $reviewer2->id)->first();
    expect($nextStep->decision_expected_by)->toBeNull();
});

test('revision response sets deadline for secondary manuscript when region has enforcement enabled', function (): void {
    Mail::fake();

    $region = Region::first();
    $region->enforce_secondary_review_deadline = true;
    $region->save();

    $reviewer = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->secondary()->in_review(secondary: true)->create([
        'region_id' => $region->id,
    ]);

    // Simulate reviewer requesting revision
    $reviewerStep = ManagementReviewStep::factory()->for($reviewer)->create([
        'manuscript_record_id' => $manuscript->id,
        'comments' => 'needs revision',
    ]);

    $this->actingAs($reviewer)->putJson("/api/manuscript-records/{$manuscript->id}/management-review-steps/{$reviewerStep->id}/revision")
        ->assertOk();

    // Author responds to revision
    $authorStep = $manuscript->refresh()->managementReviewSteps()->where('status', ManagementReviewStepStatus::ON_HOLD)->first();
    $authorStep->comments = 'here are my revisions';
    $authorStep->save();

    $this->actingAs($manuscript->user)->putJson("/api/manuscript-records/{$manuscript->id}/management-review-steps/{$authorStep->id}/revision-response")
        ->assertOk();

    // The new step back to the reviewer should have a deadline
    $nextStep = $manuscript->refresh()->managementReviewSteps()
        ->where('status', ManagementReviewStepStatus::PENDING)
        ->latest('id')
        ->first();
    expect($nextStep->decision_expected_by)->not->toBeNull();
});

test('primary manuscript still gets deadline regardless of region setting', function (): void {
    Mail::fake();

    $region = Region::first();
    $region->enforce_secondary_review_deadline = false;
    $region->save();

    $manuscript = ManuscriptRecord::factory()->filled()->create([
        'region_id' => $region->id,
    ]);
    $reviewer = User::factory()->create();

    $this->actingAs($manuscript->user)->putJson("/api/manuscript-records/{$manuscript->id}/submit-for-review", [
        'reviewer_user_id' => $reviewer->id,
    ])->assertOk();

    $step = $manuscript->managementReviewSteps()->first();
    expect($step->decision_expected_by)->not->toBeNull();
});
