<?php

use App\Enums\ManagementReviewStepStatus;
use App\Enums\Permissions\UserRole;
use App\Models\ManagementReviewDelegation;
use App\Models\ManagementReviewStep;
use App\Models\ManuscriptRecord;
use App\Models\User;
use Illuminate\Support\Facades\Event;

test('delegate can complete SECONDARY review when delegator has COMPLETE_INTERNTAL_MANAGEMENT_REVIEW permission', function (): void {
    Event::fake();

    $delegator = User::factory()->create();
    $delegator->assignRole(UserRole::DIRECTOR);

    $delegate = User::factory()->create();

    ManagementReviewDelegation::factory()->create([
        'user_id' => $delegator->id,
        'delegate_user_id' => $delegate->id,
    ]);

    $manuscript = ManuscriptRecord::factory()->secondary()->in_review(withAreviewstep: false)->create();

    $step = ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'user_id' => $delegate->id,
        'status' => ManagementReviewStepStatus::PENDING,
    ]);
    $step->setRelation('manuscriptRecord', $manuscript);

    expect($delegate->can('complete', $step))->toBeTrue();
});

test('delegate cannot complete SECONDARY review when delegator lacks COMPLETE_INTERNTAL_MANAGEMENT_REVIEW permission', function (): void {
    Event::fake();

    $delegator = User::factory()->create();
    // delegator has no director role, so no COMPLETE_INTERNTAL_MANAGEMENT_REVIEW permission

    $delegate = User::factory()->create();

    ManagementReviewDelegation::factory()->create([
        'user_id' => $delegator->id,
        'delegate_user_id' => $delegate->id,
    ]);

    $manuscript = ManuscriptRecord::factory()->secondary()->in_review(withAreviewstep: false)->create();

    $step = ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'user_id' => $delegate->id,
        'status' => ManagementReviewStepStatus::PENDING,
    ]);
    $step->setRelation('manuscriptRecord', $manuscript);

    expect($delegate->can('complete', $step))->toBeFalse();
});
