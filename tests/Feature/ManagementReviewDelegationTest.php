<?php

use App\Console\Commands\ReassignExpiredDelegationSteps;
use App\Console\Commands\ReassignStartedDelegationSteps;
use App\Enums\ManagementReviewStepStatus;
use App\Enums\Permissions\UserRole;
use App\Events\ManagementReviewStepCreated;
use App\Mail\DelegationCreatedMail;
use App\Models\ManagementReviewDelegation;
use App\Models\ManagementReviewStep;
use App\Models\ManuscriptRecord;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

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

test('creating a delegation sends an email to the mailbox, delegator, and delegate', function (): void {
    Mail::fake();

    $user = User::factory()->create();
    $delegate = User::factory()->create();

    $this->actingAs($user)->postJson('/api/user/management-review-delegations', [
        'delegate_user_id' => $delegate->id,
        'ends_at' => now()->addDays(30)->toDateTimeString(),
    ])->assertSuccessful();

    Mail::assertQueued(DelegationCreatedMail::class);
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
    expect($delegation->activitiesAsSubject)->not->toBeEmpty();
});

test('a user can cancel a scheduled (future) delegation and it is deleted', function (): void {
    $user = User::factory()->create();
    $delegation = ManagementReviewDelegation::factory()->future()->create(['user_id' => $user->id]);

    $this->actingAs($user)->deleteJson("/api/user/management-review-delegations/{$delegation->id}")
        ->assertSuccessful();

    expect(ManagementReviewDelegation::query()->find($delegation->id))->toBeNull();
});

test('ending an active delegation sets ended_early_at instead of deleting', function (): void {
    $user = User::factory()->create();
    $delegation = ManagementReviewDelegation::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)->deleteJson("/api/user/management-review-delegations/{$delegation->id}")
        ->assertSuccessful();

    expect($delegation->refresh()->ended_early_at)->not->toBeNull();
});

test('isScheduled returns true for future delegations', function (): void {
    $delegation = ManagementReviewDelegation::factory()->future()->create();

    expect($delegation->isScheduled())->toBeTrue();
    expect($delegation->isActive())->toBeFalse();
});

test('isScheduled returns false for active delegations', function (): void {
    $delegation = ManagementReviewDelegation::factory()->create();

    expect($delegation->isScheduled())->toBeFalse();
    expect($delegation->isActive())->toBeTrue();
});

test('another user cannot end someone elses delegation', function (): void {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $delegation = ManagementReviewDelegation::factory()->create(['user_id' => $user->id]);

    $this->actingAs($otherUser)->deleteJson("/api/user/management-review-delegations/{$delegation->id}")
        ->assertForbidden();
});

test('admin can forward a pending step to the active delegate', function (): void {
    Event::fake([ManagementReviewStepCreated::class]);

    $admin = User::factory()->create();
    $admin->assignRole(UserRole::ADMIN);

    $rds = User::factory()->create();
    $delegate = User::factory()->create();

    // Create step before delegation so the observer does not auto-reassign it
    $manuscript = ManuscriptRecord::factory()->in_review()->create();
    $step = ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'user_id' => $rds->id,
        'status' => ManagementReviewStepStatus::PENDING,
    ]);

    ManagementReviewDelegation::factory()->create([
        'user_id' => $rds->id,
        'delegate_user_id' => $delegate->id,
    ]);

    $this->actingAs($admin)
        ->putJson("/api/manuscript-records/{$manuscript->id}/management-review-steps/{$step->id}/forward")
        ->assertSuccessful();

    $step->refresh();
    expect($step->status)->toBe(ManagementReviewStepStatus::REASSIGN);
    expect($step->completed_at)->not->toBeNull();

    $delegateStep = ManagementReviewStep::query()
        ->where('user_id', $delegate->id)
        ->where('manuscript_record_id', $manuscript->id)
        ->first();

    expect($delegateStep)->not->toBeNull();
    expect($delegateStep->previous_step_id)->toBe($step->id);
    expect($delegateStep->status)->toBe(ManagementReviewStepStatus::PENDING);

    Event::assertDispatched(ManagementReviewStepCreated::class);
});

test('non-admin cannot forward a pending step', function (): void {
    $user = User::factory()->create();
    $rds = User::factory()->create();
    $delegate = User::factory()->create();

    ManagementReviewDelegation::factory()->create([
        'user_id' => $rds->id,
        'delegate_user_id' => $delegate->id,
    ]);

    $manuscript = ManuscriptRecord::factory()->in_review()->create();
    $step = ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'user_id' => $rds->id,
        'status' => ManagementReviewStepStatus::PENDING,
    ]);

    $this->actingAs($user)
        ->putJson("/api/manuscript-records/{$manuscript->id}/management-review-steps/{$step->id}/forward")
        ->assertForbidden();
});

test('admin cannot forward when step user has no active delegation', function (): void {
    $admin = User::factory()->create();
    $admin->assignRole(UserRole::ADMIN);

    $rds = User::factory()->create();

    $manuscript = ManuscriptRecord::factory()->in_review()->create();
    $step = ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'user_id' => $rds->id,
        'status' => ManagementReviewStepStatus::PENDING,
    ]);

    $this->actingAs($admin)
        ->putJson("/api/manuscript-records/{$manuscript->id}/management-review-steps/{$step->id}/forward")
        ->assertForbidden();
});

test('admin cannot forward a non-pending step', function (): void {
    $admin = User::factory()->create();
    $admin->assignRole(UserRole::ADMIN);

    $rds = User::factory()->create();
    $delegate = User::factory()->create();

    ManagementReviewDelegation::factory()->create([
        'user_id' => $rds->id,
        'delegate_user_id' => $delegate->id,
    ]);

    $manuscript = ManuscriptRecord::factory()->in_review()->create();
    $step = ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'user_id' => $rds->id,
        'status' => ManagementReviewStepStatus::ON_HOLD,
    ]);

    $this->actingAs($admin)
        ->putJson("/api/manuscript-records/{$manuscript->id}/management-review-steps/{$step->id}/forward")
        ->assertForbidden();
});

test('forwarded step carries over decision_expected_by from original', function (): void {
    Event::fake([ManagementReviewStepCreated::class]);

    $admin = User::factory()->create();
    $admin->assignRole(UserRole::ADMIN);

    $rds = User::factory()->create();
    $delegate = User::factory()->create();

    $deadline = now()->addDays(5)->startOfMinute();
    // Create step before delegation so the observer does not auto-reassign it
    $manuscript = ManuscriptRecord::factory()->in_review()->create();
    $step = ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'user_id' => $rds->id,
        'status' => ManagementReviewStepStatus::PENDING,
        'decision_expected_by' => $deadline,
    ]);

    ManagementReviewDelegation::factory()->create([
        'user_id' => $rds->id,
        'delegate_user_id' => $delegate->id,
    ]);

    $this->actingAs($admin)
        ->putJson("/api/manuscript-records/{$manuscript->id}/management-review-steps/{$step->id}/forward")
        ->assertSuccessful();

    $delegateStep = ManagementReviewStep::query()
        ->where('user_id', $delegate->id)
        ->where('manuscript_record_id', $manuscript->id)
        ->first();

    expect($delegateStep->decision_expected_by->startOfMinute()->toDateTimeString())
        ->toBe($deadline->toDateTimeString());
});

// --- Delegation lifecycle: start ---

test('creating an active delegation forwards existing pending steps to the delegate', function (): void {
    Event::fake([ManagementReviewStepCreated::class]);

    $user = User::factory()->create();
    $delegate = User::factory()->create();

    $manuscript = ManuscriptRecord::factory()->in_review()->create();
    $step = ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'user_id' => $user->id,
        'status' => ManagementReviewStepStatus::PENDING,
    ]);

    $this->actingAs($user)->postJson('/api/user/management-review-delegations', [
        'delegate_user_id' => $delegate->id,
        'ends_at' => now()->addDays(30)->toDateTimeString(),
    ])->assertSuccessful();

    $step->refresh();
    expect($step->status)->toBe(ManagementReviewStepStatus::REASSIGN);
    expect($step->completed_at)->not->toBeNull();

    $delegateStep = ManagementReviewStep::query()
        ->where('user_id', $delegate->id)
        ->where('manuscript_record_id', $manuscript->id)
        ->first();

    expect($delegateStep)->not->toBeNull();
    expect($delegateStep->previous_step_id)->toBe($step->id);
    expect($delegateStep->status)->toBe(ManagementReviewStepStatus::PENDING);

    Event::assertDispatched(ManagementReviewStepCreated::class);
});

test('creating a scheduled (future) delegation does not forward existing pending steps', function (): void {
    $user = User::factory()->create();
    $delegate = User::factory()->create();

    $manuscript = ManuscriptRecord::factory()->in_review()->create();
    $step = ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'user_id' => $user->id,
        'status' => ManagementReviewStepStatus::PENDING,
    ]);

    $this->actingAs($user)->postJson('/api/user/management-review-delegations', [
        'delegate_user_id' => $delegate->id,
        'starts_at' => now()->addDays(7)->toDateTimeString(),
        'ends_at' => now()->addDays(37)->toDateTimeString(),
    ])->assertSuccessful();

    $step->refresh();
    expect($step->status)->toBe(ManagementReviewStepStatus::PENDING);

    $delegateStep = ManagementReviewStep::query()
        ->where('user_id', $delegate->id)
        ->where('manuscript_record_id', $manuscript->id)
        ->first();

    expect($delegateStep)->toBeNull();
});

test('creating a delegation does not forward non-pending steps', function (): void {
    $user = User::factory()->create();
    $delegate = User::factory()->create();

    $manuscript = ManuscriptRecord::factory()->in_review()->create();
    $step = ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'user_id' => $user->id,
        'status' => ManagementReviewStepStatus::ON_HOLD,
    ]);

    $this->actingAs($user)->postJson('/api/user/management-review-delegations', [
        'delegate_user_id' => $delegate->id,
        'ends_at' => now()->addDays(30)->toDateTimeString(),
    ])->assertSuccessful();

    expect($step->refresh()->status)->toBe(ManagementReviewStepStatus::ON_HOLD);

    $delegateStep = ManagementReviewStep::query()
        ->where('user_id', $delegate->id)
        ->where('manuscript_record_id', $manuscript->id)
        ->first();

    expect($delegateStep)->toBeNull();
});

// --- Delegation lifecycle: end ---

test('ending a delegation early reassigns delegate pending steps back to original user', function (): void {
    Event::fake([ManagementReviewStepCreated::class]);

    $user = User::factory()->create();
    $delegate = User::factory()->create();

    $delegation = ManagementReviewDelegation::factory()->create([
        'user_id' => $user->id,
        'delegate_user_id' => $delegate->id,
    ]);

    $manuscript = ManuscriptRecord::factory()->in_review()->create();
    $originalStep = ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'user_id' => $user->id,
        'status' => ManagementReviewStepStatus::REASSIGN,
        'completed_at' => now(),
    ]);
    $delegateStep = ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'user_id' => $delegate->id,
        'status' => ManagementReviewStepStatus::PENDING,
        'previous_step_id' => $originalStep->id,
    ]);

    $this->actingAs($user)
        ->deleteJson("/api/user/management-review-delegations/{$delegation->id}")
        ->assertSuccessful();

    $delegateStep->refresh();
    expect($delegateStep->status)->toBe(ManagementReviewStepStatus::REASSIGN);
    expect($delegateStep->completed_at)->not->toBeNull();

    $returnedStep = ManagementReviewStep::query()
        ->where('user_id', $user->id)
        ->where('manuscript_record_id', $manuscript->id)
        ->where('status', ManagementReviewStepStatus::PENDING)
        ->first();

    expect($returnedStep)->not->toBeNull();
    expect($returnedStep->previous_step_id)->toBe($delegateStep->id);

    Event::assertDispatched(ManagementReviewStepCreated::class);
});

test('ending a delegation early reassigns to next active delegate when one exists', function (): void {
    Event::fake([ManagementReviewStepCreated::class]);

    $user = User::factory()->create();
    $delegate = User::factory()->create();
    $nextDelegate = User::factory()->create();

    $delegation = ManagementReviewDelegation::factory()->create([
        'user_id' => $user->id,
        'delegate_user_id' => $delegate->id,
        'ends_at' => now()->addHour(),
    ]);

    // Create the next active delegation for the same user
    ManagementReviewDelegation::factory()->create([
        'user_id' => $user->id,
        'delegate_user_id' => $nextDelegate->id,
        'starts_at' => null,
        'ends_at' => now()->addDays(30),
    ]);

    $manuscript = ManuscriptRecord::factory()->in_review()->create();
    $originalStep = ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'user_id' => $user->id,
        'status' => ManagementReviewStepStatus::REASSIGN,
        'completed_at' => now(),
    ]);
    $delegateStep = ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'user_id' => $delegate->id,
        'status' => ManagementReviewStepStatus::PENDING,
        'previous_step_id' => $originalStep->id,
    ]);

    $delegation->update(['ended_early_at' => now()]);

    // Directly call the action since two active delegations can't coexist via the API
    \App\Actions\Delegations\ReassignPendingStepsOnDelegationEnd::handle($delegation);

    $delegateStep->refresh();
    expect($delegateStep->status)->toBe(ManagementReviewStepStatus::REASSIGN);

    $newStep = ManagementReviewStep::query()
        ->where('user_id', $nextDelegate->id)
        ->where('manuscript_record_id', $manuscript->id)
        ->where('status', ManagementReviewStepStatus::PENDING)
        ->first();

    expect($newStep)->not->toBeNull();
    expect($newStep->previous_step_id)->toBe($delegateStep->id);
});

test('ending a delegation does not touch delegate steps not originating from this delegation', function (): void {
    $user = User::factory()->create();
    $delegate = User::factory()->create();
    $otherUser = User::factory()->create();

    $delegation = ManagementReviewDelegation::factory()->create([
        'user_id' => $user->id,
        'delegate_user_id' => $delegate->id,
    ]);

    // A step for delegate whose previous step belongs to a different user (not $user)
    $manuscript = ManuscriptRecord::factory()->in_review()->create();
    $otherOriginalStep = ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'user_id' => $otherUser->id,
        'status' => ManagementReviewStepStatus::REASSIGN,
        'completed_at' => now(),
    ]);
    $unrelatedStep = ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'user_id' => $delegate->id,
        'status' => ManagementReviewStepStatus::PENDING,
        'previous_step_id' => $otherOriginalStep->id,
    ]);

    $this->actingAs($user)
        ->deleteJson("/api/user/management-review-delegations/{$delegation->id}")
        ->assertSuccessful();

    expect($unrelatedStep->refresh()->status)->toBe(ManagementReviewStepStatus::PENDING);
});

test('expired delegation command reassigns pending delegate steps to original user', function (): void {
    Event::fake([ManagementReviewStepCreated::class]);

    $user = User::factory()->create();
    $delegate = User::factory()->create();

    $delegation = ManagementReviewDelegation::factory()->expired()->create([
        'user_id' => $user->id,
        'delegate_user_id' => $delegate->id,
    ]);

    $manuscript = ManuscriptRecord::factory()->in_review()->create();
    $originalStep = ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'user_id' => $user->id,
        'status' => ManagementReviewStepStatus::REASSIGN,
        'completed_at' => now()->subDays(2),
    ]);
    $delegateStep = ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'user_id' => $delegate->id,
        'status' => ManagementReviewStepStatus::PENDING,
        'previous_step_id' => $originalStep->id,
    ]);

    $this->artisan(ReassignExpiredDelegationSteps::class)->assertExitCode(0);

    $delegateStep->refresh();
    expect($delegateStep->status)->toBe(ManagementReviewStepStatus::REASSIGN);

    $returnedStep = ManagementReviewStep::query()
        ->where('user_id', $user->id)
        ->where('manuscript_record_id', $manuscript->id)
        ->where('status', ManagementReviewStepStatus::PENDING)
        ->first();

    expect($returnedStep)->not->toBeNull();
    expect($returnedStep->previous_step_id)->toBe($delegateStep->id);

    Event::assertDispatched(ManagementReviewStepCreated::class);
});

test('expired delegation command does not process early-ended delegations', function (): void {
    $user = User::factory()->create();
    $delegate = User::factory()->create();

    ManagementReviewDelegation::factory()->endedEarly()->create([
        'user_id' => $user->id,
        'delegate_user_id' => $delegate->id,
    ]);

    $manuscript = ManuscriptRecord::factory()->in_review()->create();
    $originalStep = ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'user_id' => $user->id,
        'status' => ManagementReviewStepStatus::REASSIGN,
        'completed_at' => now()->subDay(),
    ]);
    $delegateStep = ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'user_id' => $delegate->id,
        'status' => ManagementReviewStepStatus::PENDING,
        'previous_step_id' => $originalStep->id,
    ]);

    $this->artisan(ReassignExpiredDelegationSteps::class)->assertExitCode(0);

    expect($delegateStep->refresh()->status)->toBe(ManagementReviewStepStatus::PENDING);
});

// --- Scheduled delegation start ---

test('started delegation command forwards steps created during the scheduling window', function (): void {
    Event::fake([ManagementReviewStepCreated::class]);

    $user = User::factory()->create();
    $delegate = User::factory()->create();

    // Delegation was scheduled in the future, is now active (starts_at just passed)
    ManagementReviewDelegation::factory()->create([
        'user_id' => $user->id,
        'delegate_user_id' => $delegate->id,
        'starts_at' => now()->subHour(),   // just went active
        'ends_at' => now()->addDays(7),
    ]);

    // Step was created before the delegation went active (during scheduling window)
    $manuscript = ManuscriptRecord::factory()->in_review()->create();
    $step = ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'user_id' => $user->id,
        'status' => ManagementReviewStepStatus::PENDING,
    ]);

    $this->artisan(ReassignStartedDelegationSteps::class)->assertExitCode(0);

    $step->refresh();
    expect($step->status)->toBe(ManagementReviewStepStatus::REASSIGN);
    expect($step->completed_at)->not->toBeNull();

    $delegateStep = ManagementReviewStep::query()
        ->where('user_id', $delegate->id)
        ->where('manuscript_record_id', $manuscript->id)
        ->first();

    expect($delegateStep)->not->toBeNull();
    expect($delegateStep->previous_step_id)->toBe($step->id);
    expect($delegateStep->status)->toBe(ManagementReviewStepStatus::PENDING);

    Event::assertDispatched(ManagementReviewStepCreated::class);
});

test('started delegation command does not process immediate (non-scheduled) delegations', function (): void {
    $user = User::factory()->create();
    $delegate = User::factory()->create();

    // Create the step first so the observer does not auto-reassign it
    $manuscript = ManuscriptRecord::factory()->in_review()->create();
    $step = ManagementReviewStep::factory()->create([
        'manuscript_record_id' => $manuscript->id,
        'user_id' => $user->id,
        'status' => ManagementReviewStepStatus::PENDING,
    ]);

    // Immediate delegation (starts_at = null) — command must skip it (whereNotNull filter)
    ManagementReviewDelegation::factory()->create([
        'user_id' => $user->id,
        'delegate_user_id' => $delegate->id,
        'starts_at' => null,
        'ends_at' => now()->addDays(7),
    ]);

    $this->artisan(ReassignStartedDelegationSteps::class)->assertExitCode(0);

    expect($step->refresh()->status)->toBe(ManagementReviewStepStatus::PENDING);
});

test('started delegation command is idempotent — already forwarded steps are not re-processed', function (): void {
    $user = User::factory()->create();
    $delegate = User::factory()->create();

    ManagementReviewDelegation::factory()->create([
        'user_id' => $user->id,
        'delegate_user_id' => $delegate->id,
        'starts_at' => now()->subDays(3),
        'ends_at' => now()->addDays(4),
    ]);

    // No pending steps for user — all already forwarded when command ran on day 1
    $this->artisan(ReassignStartedDelegationSteps::class)->assertExitCode(0);

    expect(ManagementReviewStep::query()->where('user_id', $delegate->id)->count())->toBe(0);
});
