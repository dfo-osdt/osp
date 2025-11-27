<?php

use App\Mail\ManagementReviewDueMail;
use App\Models\ManagementReviewStep;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

test('it sends notifications on business days', function (): void {
    Mail::fake();

    // Set to a Monday (business day)
    \Carbon\Carbon::setTestNow('2024-10-07 10:00:00'); // Monday, October 7, 2024

    $user = User::factory()->create();
    ManagementReviewStep::factory()->create([
        'user_id' => $user->id,
        'decision_expected_by' => now()->subDay(),
    ]);

    $this->artisan('osp:send-due-management-review-notifications')
        ->expectsOutput('Checking for due and overdue management reviews...')
        ->expectsOutput('Management review notifications have been queued.')
        ->assertExitCode(0);

    Mail::assertQueued(ManagementReviewDueMail::class);

    \Carbon\Carbon::setTestNow();
});

test('it skips notifications on weekends', function (): void {
    Mail::fake();

    // Set to a Saturday (non-business day)
    \Carbon\Carbon::setTestNow('2024-10-05 10:00:00'); // Saturday, October 5, 2024

    $user = User::factory()->create();
    ManagementReviewStep::factory()->create([
        'user_id' => $user->id,
        'decision_expected_by' => now()->subDay(),
    ]);

    $this->artisan('osp:send-due-management-review-notifications')
        ->expectsOutput('Skipping notification - today is not a business day.')
        ->assertExitCode(0);

    Mail::assertNothingQueued();

    \Carbon\Carbon::setTestNow();
});

test('it skips notifications on holidays', function (): void {
    Mail::fake();

    // Set to Christmas Day (holiday)
    \Carbon\Carbon::setTestNow('2024-12-25 10:00:00'); // Wednesday, December 25, 2024

    $user = User::factory()->create();
    ManagementReviewStep::factory()->create([
        'user_id' => $user->id,
        'decision_expected_by' => now()->subDay(),
    ]);

    $this->artisan('osp:send-due-management-review-notifications')
        ->expectsOutput('Skipping notification - today is not a business day.')
        ->assertExitCode(0);

    Mail::assertNothingQueued();

    \Carbon\Carbon::setTestNow();
});

test('it sends notifications on weekends when forced', function (): void {
    Mail::fake();

    // Set to a Saturday (non-business day)
    \Carbon\Carbon::setTestNow('2024-10-05 10:00:00'); // Saturday, October 5, 2024

    $user = User::factory()->create();
    ManagementReviewStep::factory()->create([
        'user_id' => $user->id,
        'decision_expected_by' => now()->subDay(),
    ]);

    $this->artisan('osp:send-due-management-review-notifications --force')
        ->expectsOutput('Forcing execution on a non-business day...')
        ->expectsOutput('Checking for due and overdue management reviews...')
        ->expectsOutput('Management review notifications have been queued.')
        ->assertExitCode(0);

    Mail::assertQueued(ManagementReviewDueMail::class);

    \Carbon\Carbon::setTestNow();
});

test('it sends notifications on holidays when forced', function (): void {
    Mail::fake();

    // Set to Christmas Day (holiday)
    \Carbon\Carbon::setTestNow('2024-12-25 10:00:00'); // Wednesday, December 25, 2024

    $user = User::factory()->create();
    ManagementReviewStep::factory()->create([
        'user_id' => $user->id,
        'decision_expected_by' => now()->subDay(),
    ]);

    $this->artisan('osp:send-due-management-review-notifications --force')
        ->expectsOutput('Forcing execution on a non-business day...')
        ->expectsOutput('Checking for due and overdue management reviews...')
        ->expectsOutput('Management review notifications have been queued.')
        ->assertExitCode(0);

    Mail::assertQueued(ManagementReviewDueMail::class);

    \Carbon\Carbon::setTestNow();
});
