<?php

use App\Enums\ManagementReviewStepDecision;
use App\Enums\ManagementReviewStepStatus;
use App\Enums\ManuscriptRecordStatus;
use App\Enums\Permissions\UserRole;
use App\Events\ManuscriptRecordWithdrawnByAuthor;
use App\Mail\ManuscriptManagementReviewComplete;
use App\Mail\ReviewStepNotificationMail;
use App\Models\ManagementReviewStep;
use App\Models\ManuscriptRecord;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

test('a reviewer can view all review steps associated with manuscript', function () {
    $owner = User::factory()->create();
    $reviewer = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->filled()->create(['user_id' => $owner->id]);

    // no review steps yet, so the reviewer should not be able to view any
    $this->actingAs($reviewer)->getJson('/api/manuscript-records/'.$manuscript->id.'/management-review-steps')
        ->assertForbidden();

    // owner can view but empty list
    $response = $this->actingAs($owner)->getJson('/api/manuscript-records/'.$manuscript->id.'/management-review-steps')
        ->assertOk();

    expect($response->json('data'))->toBeEmpty();

    // add a review step
    $manuscript->status = ManuscriptRecordStatus::IN_REVIEW;
    $reviewStep = ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'user_id' => $reviewer->id,
    ]);

    // check that now the reviewer should be able to view the review step
    $response = $this->actingAs($reviewer)->getJson('/api/manuscript-records/'.$manuscript->id.'/management-review-steps')
        ->assertOk();

    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data.0.data.id'))->toBe($reviewStep->id);
    expect($response->json('data.0.can.update'))->toBeTrue();

    // check that the owner can view the review step, but cannot update it
    $response = $this->actingAs($owner)->getJson('/api/manuscript-records/'.$manuscript->id.'/management-review-steps')
        ->assertOk();

    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data.0.data.id'))->toBe($reviewStep->id);
    expect($response->json('data.0.can.update'))->toBeFalse();
});

test('a reviewer can update their review comments', function () {
    $reviewer = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->in_review()->has(ManagementReviewStep::factory()->for($reviewer))->create();

    $response = $this->actingAs($reviewer)->putJson('/api/manuscript-records/'.$manuscript->id.'/management-review-steps/'.$manuscript->managementReviewSteps->first()->id, [
        'comments' => 'test comments',
    ])->assertOk();

    expect($response->json('data.comments'))->toBe('test comments');
    expect($manuscript->managementReviewSteps->first()->refresh()->comments)->toBe('test comments');
});

test('a reviewer can refer the review to the next reviewer', function () {
    Mail::fake();

    // to send to the next step, a comment is required
    $reviewer = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->in_review()->has(ManagementReviewStep::factory()->for($reviewer)->set('comments', 'a comment here'))->create();
    $reviewer2 = User::factory()->create();

    $this->actingAs($reviewer)
        ->putJson('/api/manuscript-records/'.$manuscript->id.'/management-review-steps/'.$manuscript->managementReviewSteps->first()->id.'/refer', [
            'next_user_id' => $reviewer2->id,
            'comments' => 'a comment here',
        ])->assertOk();

    // assert email to next reviewer queued
    Mail::assertQueued(ReviewStepNotificationMail::class);
});

test('a reviewer can ask for revision and send back to the author for clarifications and can reply to continue management review', function () {

    Mail::fake();

    $reviewer = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->in_review()->has(ManagementReviewStep::factory()->for($reviewer)->set('comments', 'a comment is required'))->create();

    $response = $this->actingAs($reviewer)->putJson('/api/manuscript-records/'.$manuscript->id.'/management-review-steps/'.$manuscript->managementReviewSteps->first()->id.'/revision')
        ->assertOk();

    expect($response->json('data.decision'))->toBe(ManagementReviewStepDecision::REVISION->value);
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
    $response = $this->actingAs($manuscript->user)->putJson('/api/manuscript-records/'.$manuscript->id.'/management-review-steps/'.$nextReviewSteps->id, $data)
        ->assertOk();
    expect($response->json('data.comments'))->toBe('response to reviewer');

    // make sure the author cannot complete the review
    $response = $this->actingAs($manuscript->user)->putJson('/api/manuscript-records/'.$manuscript->id.'/management-review-steps/'.$nextReviewSteps->id.'/complete')
        ->assertForbidden();

    $response = $this->actingAs($manuscript->user)->putJson('/api/manuscript-records/'.$manuscript->id.'/management-review-steps/'.$nextReviewSteps->id.'/revision-response')
        ->assertOK();

    expect($manuscript->refresh()->managementReviewSteps->last()->user_id)->toBe($reviewer->id);

    Mail::assertQueued(ReviewStepNotificationMail::class, 2);
});

test('an author can withdraw their manuscript when revision are required on their manuscript', function () {
    Mail::fake();

    $reviewer = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->in_review()->has(ManagementReviewStep::factory()->for($reviewer)->set('comments', 'a comment is required'))->create();

    $response = $this->actingAs($reviewer)->putJson('/api/manuscript-records/'.$manuscript->id.'/management-review-steps/'.$manuscript->managementReviewSteps->first()->id.'/revision')
        ->assertOk();

    Mail::assertQueued(ReviewStepNotificationMail::class);

    $nextReviweStep = $manuscript->refresh()->managementReviewSteps->last();

    Event::fake();

    // author can withdraw the manuscript through the flagged review step
    $response = $this->actingAs($manuscript->user)->putJson(
        '/api/manuscript-records/'.$manuscript->id.'/management-review-steps/'.$nextReviweStep->id.'/withdraw',
        ['comments' => 'withdrawn by author']
    )->assertOk();

    expect($response->json('data.status'))->toBe(ManagementReviewStepStatus::COMPLETED->value);
    expect($response->json('data.decision'))->toBe(ManagementReviewStepDecision::WITHDRAWN->value);
    expect($response->json('data.comments'))->toBe('withdrawn by author');

    Event::assertDispatched(ManuscriptRecordWithdrawnByAuthor::class);
});

test('a reviewer can approve and complete the review of a third-party manuscript', function () {
    Mail::fake();
    $reviewer = User::factory()->create();
    $manuscript = ManuscriptRecord::factory()->in_review()->has(ManagementReviewStep::factory()->for($reviewer))->create();

    $this->actingAs($reviewer)
        ->putJson('/api/manuscript-records/'.$manuscript->id.'/management-review-steps/'.$manuscript->managementReviewSteps->first()->id.'/complete')
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
    $this->actingAs($reviewer)->putJson('/api/manuscript-records/'.$manuscript->id.'/management-review-steps/'.$manuscript->managementReviewSteps->first()->id.'/reassign')
        ->assertStatus(422);

    $this->actingAs($reviewer)->putJson('/api/manuscript-records/'.$manuscript->id.'/management-review-steps/'.$manuscript->managementReviewSteps->first()->id.'/reassign', [
        'next_user_id' => $reviewer2->id,
    ])->assertOk();

    // assert email to next reviewer queued
    Mail::assertQueued(ReviewStepNotificationMail::class);
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

test('only a director can complete an internal managment reviw', function () {
    $regularReviewer = User::factory()->create();
    $director = User::factory()->withRoles([UserRole::DIRECTOR])->create();

    $manuscript = ManuscriptRecord::factory()
        ->in_review()->secondary()
        ->has(ManagementReviewStep::factory()
            ->for($regularReviewer))->create();

    // this regular user should not be able to complete the review
    $this->actingAs($regularReviewer)->putJson('/api/manuscript-records/'.$manuscript->id.'/management-review-steps/'.$manuscript->managementReviewSteps->first()->id.'/complete')
        ->assertForbidden();

    $manuscript = ManuscriptRecord::factory()
        ->in_review()->secondary()
        ->has(ManagementReviewStep::factory()
            ->for($director))->create();

    // but a director should be able to complete the review
    $this->actingAs($director)->putJson('/api/manuscript-records/'.$manuscript->id.'/management-review-steps/'.$manuscript->managementReviewSteps->first()->id.'/complete')
        ->assertOk();

});
