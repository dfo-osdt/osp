<?php

use App\Enums\ManagementReviewStepDecision;
use App\Enums\ManagementReviewStepStatus;
use App\Enums\ManuscriptRecordStatus;
use App\Events\ManuscriptRecordWithdrawnByAuthor;
use App\Mail\ManuscriptManagementReviewComplete;
use App\Mail\ManuscriptWithheldMail;
use App\Mail\ReviewStepNotificationMail;
use App\Models\ManagementReviewStep;
use App\Models\ManuscriptRecord;
use App\Models\User;

test('a reviewer can view all review steps associated with manuscript', function () {
    $owner = User::factory()->create();
    $reviewer = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->in_review()->create(['user_id' => $owner->id]);

    // no review steps yet, so the reviewer should not be able to view any
    $this->actingAs($reviewer)->getJson('/api/manuscript-records/' . $manuscript->id . '/management-review-steps')
        ->assertForbidden();

    // owner can view but empty list
    $response = $this->actingAs($owner)->getJson('/api/manuscript-records/' . $manuscript->id . '/management-review-steps')
        ->assertOk();

    expect($response->json('data'))->toBeEmpty();

    // add a review step
    $manuscript->status = ManuscriptRecordStatus::IN_REVIEW;
    $reviewStep = ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'user_id' => $reviewer->id,
    ]);

    // check that now the reviewer should be able to view the review step
    $response = $this->actingAs($reviewer)->getJson('/api/manuscript-records/' . $manuscript->id . '/management-review-steps')
        ->assertOk();

    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data.0.data.id'))->toBe($reviewStep->id);
    expect($response->json('data.0.can.update'))->toBeTrue();

    // check that the owner can view the review step, but cannot update it
    $response = $this->actingAs($owner)->getJson('/api/manuscript-records/' . $manuscript->id . '/management-review-steps')
        ->assertOk();

    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data.0.data.id'))->toBe($reviewStep->id);
    expect($response->json('data.0.can.update'))->toBeFalse();
});

test('a reviewer can update their review comments', function () {
    $reviewer = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->in_review()->has(ManagementReviewStep::factory()->for($reviewer))->create();

    $response = $this->actingAs($reviewer)->putJson('/api/manuscript-records/' . $manuscript->id . '/management-review-steps/' . $manuscript->managementReviewSteps->first()->id, [
        'comments' => 'test comments',
    ])->assertOk();

    expect($response->json('data.comments'))->toBe('test comments');
    expect($manuscript->managementReviewSteps->first()->refresh()->comments)->toBe('test comments');
});

test('a reviewer can approve and send to the next reviewer', function () {
    Mail::fake();

    // to send to the next step, a comment is required
    $reviewer = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->in_review()->has(ManagementReviewStep::factory()->for($reviewer)->set('comments', 'a comment here'))->create();
    $reviewer2 = User::factory()->create();

    $response = $this->actingAs($reviewer)->putJson('/api/manuscript-records/' . $manuscript->id . '/management-review-steps/' . $manuscript->managementReviewSteps->first()->id . '/approve', [
        'next_user_id' => $reviewer2->id,
    ])->assertOk();

    // assert email to next reviewer queued
    Mail::assertQueued(ReviewStepNotificationMail::class);
});

test('a reviwer can flag and send back to the author for clarifications and can reply to continue management review', function () {

    Mail::fake();

    $reviewer = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->in_review()->has(ManagementReviewStep::factory()->for($reviewer)->set('comments', 'a comment is required'))->create();

    $response = $this->actingAs($reviewer)->putJson('/api/manuscript-records/' . $manuscript->id . '/management-review-steps/' . $manuscript->managementReviewSteps->first()->id . '/flag')
        ->assertOk();

    expect($response->json('data.decision'))->toBe(ManagementReviewStepDecision::FLAGGED->value);
    expect($response->json('data.status'))->toBe(ManagementReviewStepStatus::COMPLETED->value);

    $nextReviewSteps = $manuscript->refresh()->managementReviewSteps->last();
    expect($nextReviewSteps->user_id)->toBe($manuscript->user_id);
    expect($nextReviewSteps->status)->toBe(ManagementReviewStepStatus::ON_HOLD);

    Mail::assertQueued(ReviewStepNotificationMail::class);

    // the author should be able to view and respond to the review step
    $data = [
        'comments' => 'response to reviewer',
    ];

    // update comment
    $response = $this->actingAs($manuscript->user)->putJson('/api/manuscript-records/' . $manuscript->id . '/management-review-steps/' . $nextReviewSteps->id, $data)
        ->assertOk();
    expect($response->json('data.comments'))->toBe('response to reviewer');

    // make sure the author cannot complete the review
    $response = $this->actingAs($manuscript->user)->putJson('/api/manuscript-records/' . $manuscript->id . '/management-review-steps/' . $nextReviewSteps->id . '/approve')
        ->assertForbidden();

    $response = $this->actingAs($manuscript->user)->putJson('/api/manuscript-records/' . $manuscript->id . '/management-review-steps/' . $nextReviewSteps->id . '/flagged-response')
        ->assertOK();

    expect($manuscript->refresh()->managementReviewSteps->last()->user_id)->toBe($reviewer->id);

    Mail::assertQueued(ReviewStepNotificationMail::class, 2);
});

test('a author can withdraw their manuscript when their manuscript is flagged', function () {
    Mail::fake();

    $reviewer = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->in_review()->has(ManagementReviewStep::factory()->for($reviewer)->set('comments', 'a comment is required'))->create();

    $response = $this->actingAs($reviewer)->putJson('/api/manuscript-records/' . $manuscript->id . '/management-review-steps/' . $manuscript->managementReviewSteps->first()->id . '/flag')
        ->assertOk();

    Mail::assertQueued(ReviewStepNotificationMail::class);

    $nextReviweStep = $manuscript->refresh()->managementReviewSteps->last();

    Event::fake();

    // author can withdraw the manuscript through the flagged review step
    $response = $this->actingAs($manuscript->user)->putJson(
        '/api/manuscript-records/' . $manuscript->id . '/management-review-steps/' . $nextReviweStep->id . '/withdraw',
        ['comments' => 'withdrawn by author']
    )->assertOk();

    expect($response->json('data.status'))->toBe(ManagementReviewStepStatus::COMPLETED->value);
    expect($response->json('data.decision'))->toBe(ManagementReviewStepDecision::WITHDRAWN->value);
    expect($response->json('data.comments'))->toBe('withdrawn by author');

    Event::assertDispatched(ManuscriptRecordWithdrawnByAuthor::class);
});

test('a reviewer can approve and complete the review', function () {
    Mail::fake();
    $reviewer = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->in_review()->has(ManagementReviewStep::factory()->for($reviewer))->create();

    $response = $this->actingAs($reviewer)
        ->putJson('/api/manuscript-records/' . $manuscript->id . '/management-review-steps/' . $manuscript->managementReviewSteps->first()->id . '/approve')
        ->assertOk();

    Mail::assertQueued(ManuscriptManagementReviewComplete::class);
});

test('a reviewer can reassign and send to the next reviewer', function () {
    Mail::fake();

    // to send to the next step, a comment is required
    $reviewer = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->in_review()->has(ManagementReviewStep::factory()->for($reviewer)->set('comments', 'a comment is required'))->create();
    $reviewer2 = User::factory()->create();

    // no next user id provided, should fail
    $this->actingAs($reviewer)->putJson('/api/manuscript-records/' . $manuscript->id . '/management-review-steps/' . $manuscript->managementReviewSteps->first()->id . '/reassign')
        ->assertStatus(422);

    $response = $this->actingAs($reviewer)->putJson('/api/manuscript-records/' . $manuscript->id . '/management-review-steps/' . $manuscript->managementReviewSteps->first()->id . '/reassign', [
        'next_user_id' => $reviewer2->id,
    ])->assertOk();

    // assert email to next reviewer queued
    Mail::assertQueued(ReviewStepNotificationMail::class);
});

test('a reviewer can withhold and send to the next reviewer', function () {
    Mail::fake();

    // to send to the next step, a comment is required
    $reviewer = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->in_review()->has(ManagementReviewStep::factory()->for($reviewer)->set('comments', 'a comment is required'))->create();
    $reviewer2 = User::factory()->create();

    $response = $this->actingAs($reviewer)->putJson('/api/manuscript-records/' . $manuscript->id . '/management-review-steps/' . $manuscript->managementReviewSteps->first()->id . '/withhold', [
        'next_user_id' => $reviewer2->id,
    ])->assertOk();

    expect($response->json('data.decision'))->toBe(ManagementReviewStepDecision::WITHHELD->value);
    expect($response->json('data.status'))->toBe(ManagementReviewStepStatus::COMPLETED->value);

    // assert email to next reviewer queued
    Mail::assertQueued(ReviewStepNotificationMail::class);
});

test('a reviewer that has director permission can withhold and complete the review', function () {
    Mail::fake();
    $reviewer = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->in_review()->has(ManagementReviewStep::factory()->for($reviewer)->set('comments', 'a comment is required here'))->create();

    // user does not have director permission, should be forbidden
    $response = $this->actingAs($reviewer)
        ->putJson('/api/manuscript-records/' . $manuscript->id . '/management-review-steps/' . $manuscript->managementReviewSteps->first()->id . '/withhold')
        ->assertForbidden();

    Mail::assertNothingQueued();

    // add director permission
    $reviewer->assignRole('director');

    // should succeed
    $response = $this->actingAs($reviewer)
        ->putJson('/api/manuscript-records/' . $manuscript->id . '/management-review-steps/' . $manuscript->managementReviewSteps->first()->id . '/withhold')
        ->assertOk();

    Mail::assertQueued(ManuscriptWithheldMail::class);
});

test('a reviewer can list their reviews', function () {
    $reviewer = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->in_review()->has(ManagementReviewStep::factory()->for($reviewer))->create();

    $response = $this->actingAs($reviewer)->getJson('/api/my/management-review-steps')
        ->assertOk();

    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data.0.data.id'))->toBe($manuscript->managementReviewSteps->first()->id);
    expect($response->json('data.0.data.manuscript_record'))->toBeTruthy();
});
