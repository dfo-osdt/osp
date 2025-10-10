<?php

use App\Mail\ManagementReviewPendingMail;
use App\Models\ManagementReviewStep;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

test('it sends notifications on Mondays', function () {
    Mail::fake();

    // Set to a Monday (business day)
    \Carbon\Carbon::setTestNow('2024-10-07 10:00:00'); // Monday, October 7, 2024

    $user = User::factory()->create();
    ManagementReviewStep::factory()->create([
        'user_id' => $user->id,
        'created_at' => now()->subBusinessDays(5),
    ]);

    $this->artisan('osp:send-pending-management-review-notifications')
        ->expectsOutput('Checking for pending management reviews...')
        ->expectsOutput('Pending management review notifications have been queued.')
        ->assertExitCode(0);

    Mail::assertQueued(ManagementReviewPendingMail::class);

    \Carbon\Carbon::setTestNow();
});

test('it skips notifications on Tuesday', function () {
    Mail::fake();

    // Set to a Tuesday (business day but not Monday)
    \Carbon\Carbon::setTestNow('2024-10-08 10:00:00'); // Tuesday, October 8, 2024

    $user = User::factory()->create();
    ManagementReviewStep::factory()->create([
        'user_id' => $user->id,
        'created_at' => now()->subBusinessDays(5),
    ]);

    $this->artisan('osp:send-pending-management-review-notifications')
        ->expectsOutput('Skipping notification - today is not Monday.')
        ->assertExitCode(0);

    Mail::assertNothingQueued();

    \Carbon\Carbon::setTestNow();
});

test('it skips notifications on weekends', function () {
    Mail::fake();

    // Set to a Saturday (non-business day)
    \Carbon\Carbon::setTestNow('2024-10-05 10:00:00'); // Saturday, October 5, 2024

    $user = User::factory()->create();
    ManagementReviewStep::factory()->create([
        'user_id' => $user->id,
        'created_at' => now()->subBusinessDays(5),
    ]);

    $this->artisan('osp:send-pending-management-review-notifications')
        ->expectsOutput('Skipping notification - today is not Monday.')
        ->assertExitCode(0);

    Mail::assertNothingQueued();

    \Carbon\Carbon::setTestNow();
});

test('it skips notifications on holidays', function () {
    Mail::fake();

    // Set to Monday Christmas (holiday)
    \Carbon\Carbon::setTestNow('2023-12-25 10:00:00'); // Monday, December 25, 2023

    $user = User::factory()->create();
    ManagementReviewStep::factory()->create([
        'user_id' => $user->id,
        'created_at' => now()->subBusinessDays(5),
    ]);

    $this->artisan('osp:send-pending-management-review-notifications')
        ->expectsOutput('Skipping notification - today is not a business day.')
        ->assertExitCode(0);

    Mail::assertNothingQueued();

    \Carbon\Carbon::setTestNow();
});

test('it sends notifications on Tuesday when forced', function () {
    Mail::fake();

    // Set to a Tuesday (business day but not Monday)
    \Carbon\Carbon::setTestNow('2024-10-08 10:00:00'); // Tuesday, October 8, 2024

    $user = User::factory()->create();
    ManagementReviewStep::factory()->create([
        'user_id' => $user->id,
        'created_at' => now()->subBusinessDays(5),
    ]);

    $this->artisan('osp:send-pending-management-review-notifications --force')
        ->expectsOutput('Forcing execution...')
        ->expectsOutput('Checking for pending management reviews...')
        ->expectsOutput('Pending management review notifications have been queued.')
        ->assertExitCode(0);

    Mail::assertQueued(ManagementReviewPendingMail::class);

    \Carbon\Carbon::setTestNow();
});

test('it sends notifications on weekends when forced', function () {
    Mail::fake();

    // Set to a Saturday (non-business day)
    \Carbon\Carbon::setTestNow('2024-10-05 10:00:00'); // Saturday, October 5, 2024

    $user = User::factory()->create();
    ManagementReviewStep::factory()->create([
        'user_id' => $user->id,
        'created_at' => now()->subBusinessDays(5),
    ]);

    $this->artisan('osp:send-pending-management-review-notifications --force')
        ->expectsOutput('Forcing execution...')
        ->expectsOutput('Checking for pending management reviews...')
        ->expectsOutput('Pending management review notifications have been queued.')
        ->assertExitCode(0);

    Mail::assertQueued(ManagementReviewPendingMail::class);

    \Carbon\Carbon::setTestNow();
});

test('it sends notifications on holidays when forced', function () {
    Mail::fake();

    // Set to Monday Christmas (holiday)
    \Carbon\Carbon::setTestNow('2023-12-25 10:00:00'); // Monday, December 25, 2023

    $user = User::factory()->create();
    ManagementReviewStep::factory()->create([
        'user_id' => $user->id,
        'created_at' => now()->subBusinessDays(5),
    ]);

    $this->artisan('osp:send-pending-management-review-notifications --force')
        ->expectsOutput('Forcing execution...')
        ->expectsOutput('Checking for pending management reviews...')
        ->expectsOutput('Pending management review notifications have been queued.')
        ->assertExitCode(0);

    Mail::assertQueued(ManagementReviewPendingMail::class);

    \Carbon\Carbon::setTestNow();
});
