<?php

use App\Mail\ManagementReviewPendingMail;
use App\Models\ManagementReviewStep;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

test('it sends notifications when there are pending reviews', function () {
    Mail::fake();

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
});

test('it does not send notifications when there are no old pending reviews', function () {
    Mail::fake();

    $user = User::factory()->create();
    ManagementReviewStep::factory()->create([
        'user_id' => $user->id,
        'created_at' => now()->subBusinessDays(2),
    ]);

    $this->artisan('osp:send-pending-management-review-notifications')
        ->expectsOutput('Checking for pending management reviews...')
        ->expectsOutput('Pending management review notifications have been queued.')
        ->assertExitCode(0);

    Mail::assertNothingQueued();
});
