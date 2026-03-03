<?php

use App\Enums\ManagementReviewStepStatus;
use App\Events\ManagementReviewStepCreated;
use App\Models\ManagementReviewDelegation;
use App\Models\ManagementReviewStep;
use App\Models\ManuscriptRecord;
use App\Models\User;
use Illuminate\Support\Facades\Event;

test('a user can list their delegations', function (): void {
    $user = User::factory()->create();
    ManagementReviewDelegation::factory()->count(3)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->getJson('/api/user/management-review-delegations')
        ->assertSuccessful();

    expect($response->json('data'))->toHaveCount(3);
});

test('a user can create a delegation', function (): void {
    $user = User::factory()->create();
    $delegate = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/user/management-review-delegations', [
        'delegate_user_id' => $delegate->id,
        'ends_at' => now()->addDays(30)->toDateTimeString(),
    ])->assertSuccessful();

    expect($response->json('data.delegate_user_id'))->toBe($delegate->id);
    expect($response->json('data.is_active'))->toBeTrue();
});

test('a user can end a delegation early', function (): void {
    $user = User::factory()->create();
    $delegation = ManagementReviewDelegation::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->deleteJson("/api/user/management-review-delegations/{$delegation->id}")
        ->assertSuccessful();

    expect($response->json('data.ended_early_at'))->not->toBeNull();
    expect($delegation->refresh()->ended_early_at)->not->toBeNull();
});

test('a user cannot delegate to themselves', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)->postJson('/api/user/management-review-delegations', [
        'delegate_user_id' => $user->id,
        'ends_at' => now()->addDays(30)->toDateTimeString(),
    ])->assertUnprocessable();
});

test('a user cannot create two active delegations', function (): void {
    $user = User::factory()->create();
    $delegate1 = User::factory()->create();
    $delegate2 = User::factory()->create();

    ManagementReviewDelegation::factory()->create([
        'user_id' => $user->id,
        'delegate_user_id' => $delegate1->id,
    ]);

    $this->actingAs($user)->postJson('/api/user/management-review-delegations', [
        'delegate_user_id' => $delegate2->id,
        'ends_at' => now()->addDays(30)->toDateTimeString(),
    ])->assertUnprocessable();
});

test('cannot create a delegation that overlaps with a future delegation', function (): void {
    $user = User::factory()->create();
    $delegate = User::factory()->create();

    // Create a future delegation: starts in 10 days, ends in 20 days
    ManagementReviewDelegation::factory()->create([
        'user_id' => $user->id,
        'delegate_user_id' => $delegate->id,
        'starts_at' => now()->addDays(10),
        'ends_at' => now()->addDays(20),
    ]);

    $delegate2 = User::factory()->create();

    // Try to create a delegation that overlaps: starts now, ends in 15 days
    $this->actingAs($user)->postJson('/api/user/management-review-delegations', [
        'delegate_user_id' => $delegate2->id,
        'ends_at' => now()->addDays(15)->toDateTimeString(),
    ])->assertUnprocessable();
});

test('a delegate cannot create their own delegation (no chaining)', function (): void {
    $user = User::factory()->create();
    $delegate = User::factory()->create();
    $thirdUser = User::factory()->create();

    ManagementReviewDelegation::factory()->create([
        'user_id' => $user->id,
        'delegate_user_id' => $delegate->id,
    ]);

    $this->actingAs($delegate)->postJson('/api/user/management-review-delegations', [
        'delegate_user_id' => $thirdUser->id,
        'ends_at' => now()->addDays(30)->toDateTimeString(),
    ])->assertForbidden();
});

test('auto-reassign triggers on new PENDING step for user with active delegation', function (): void {
    Event::fake([ManagementReviewStepCreated::class]);

    $user = User::factory()->create();
    $delegate = User::factory()->create();

    ManagementReviewDelegation::factory()->create([
        'user_id' => $user->id,
        'delegate_user_id' => $delegate->id,
    ]);

    $manuscript = ManuscriptRecord::factory()->in_review()->create();

    $step = ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'user_id' => $user->id,
        'status' => ManagementReviewStepStatus::PENDING,
    ]);

    // Original step should be marked as REASSIGN
    $step->refresh();
    expect($step->status)->toBe(ManagementReviewStepStatus::REASSIGN);
    expect($step->completed_at)->not->toBeNull();

    // Delegate step should exist
    $delegateStep = ManagementReviewStep::query()
        ->where('user_id', $delegate->id)
        ->where('manuscript_record_id', $manuscript->id)
        ->first();

    expect($delegateStep)->not->toBeNull();
    expect($delegateStep->previous_step_id)->toBe($step->id);
    expect($delegateStep->status)->toBe(ManagementReviewStepStatus::PENDING);

    Event::assertDispatched(ManagementReviewStepCreated::class);
});

test('auto-reassign does NOT trigger for non-PENDING steps', function (): void {
    $user = User::factory()->create();
    $delegate = User::factory()->create();

    ManagementReviewDelegation::factory()->create([
        'user_id' => $user->id,
        'delegate_user_id' => $delegate->id,
    ]);

    $manuscript = ManuscriptRecord::factory()->in_review()->create();

    $step = ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'user_id' => $user->id,
        'status' => ManagementReviewStepStatus::ON_HOLD,
    ]);

    // Should not have created a delegate step
    $delegateStep = ManagementReviewStep::query()
        ->where('user_id', $delegate->id)
        ->where('manuscript_record_id', $manuscript->id)
        ->first();

    expect($delegateStep)->toBeNull();
    expect($step->refresh()->status)->toBe(ManagementReviewStepStatus::ON_HOLD);
});

test('expired delegation does not trigger auto-reassign', function (): void {
    $user = User::factory()->create();
    $delegate = User::factory()->create();

    ManagementReviewDelegation::factory()->expired()->create([
        'user_id' => $user->id,
        'delegate_user_id' => $delegate->id,
    ]);

    $manuscript = ManuscriptRecord::factory()->in_review()->create();

    $step = ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'user_id' => $user->id,
        'status' => ManagementReviewStepStatus::PENDING,
    ]);

    $delegateStep = ManagementReviewStep::query()
        ->where('user_id', $delegate->id)
        ->where('manuscript_record_id', $manuscript->id)
        ->first();

    expect($delegateStep)->toBeNull();
    expect($step->refresh()->status)->toBe(ManagementReviewStepStatus::PENDING);
});

test('future delegation does not trigger auto-reassign', function (): void {
    $user = User::factory()->create();
    $delegate = User::factory()->create();

    ManagementReviewDelegation::factory()->future()->create([
        'user_id' => $user->id,
        'delegate_user_id' => $delegate->id,
    ]);

    $manuscript = ManuscriptRecord::factory()->in_review()->create();

    $step = ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'user_id' => $user->id,
        'status' => ManagementReviewStepStatus::PENDING,
    ]);

    $delegateStep = ManagementReviewStep::query()
        ->where('user_id', $delegate->id)
        ->where('manuscript_record_id', $manuscript->id)
        ->first();

    expect($delegateStep)->toBeNull();
    expect($step->refresh()->status)->toBe(ManagementReviewStepStatus::PENDING);
});

test('original step gets delegation comment on auto-reassign', function (): void {
    Event::fake([ManagementReviewStepCreated::class]);

    $user = User::factory()->create();
    $delegate = User::factory()->create();

    ManagementReviewDelegation::factory()->create([
        'user_id' => $user->id,
        'delegate_user_id' => $delegate->id,
        'comment' => 'I am on vacation',
    ]);

    $manuscript = ManuscriptRecord::factory()->in_review()->create();

    $step = ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'user_id' => $user->id,
    ]);

    expect($step->refresh()->comments)->toBe('I am on vacation');
});

test('delegation activity is logged', function (): void {
    $user = User::factory()->create();
    $delegate = User::factory()->create();

    $this->actingAs($user)->postJson('/api/user/management-review-delegations', [
        'delegate_user_id' => $delegate->id,
        'ends_at' => now()->addDays(30)->toDateTimeString(),
    ])->assertSuccessful();

    $delegation = ManagementReviewDelegation::query()->first();
    expect($delegation->activities)->not->toBeEmpty();
});

test('another user cannot end someone elses delegation', function (): void {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $delegation = ManagementReviewDelegation::factory()->create(['user_id' => $user->id]);

    $this->actingAs($otherUser)->deleteJson("/api/user/management-review-delegations/{$delegation->id}")
        ->assertForbidden();
});
