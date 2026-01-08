<?php

use App\Enums\ManuscriptRecordStatus;
use App\Mail\JournalAcceptancePendingMail;
use App\Models\ManuscriptRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

test('it sends notifications when there are pending manuscripts', function (): void {
    Mail::fake();

    $user = User::factory()->create();
    ManuscriptRecord::factory()->create([
        'user_id' => $user->id,
        'status' => ManuscriptRecordStatus::REVIEWED,
        'reviewed_at' => now()->subMonths(2),
    ]);

    $this->artisan('osp:send-pending-journal-acceptance-notifications')
        ->expectsOutput('Checking for manuscripts that need status updates...')
        ->expectsOutput('Manuscript status update reminder notifications have been queued.')
        ->assertExitCode(0);

    Mail::assertQueued(JournalAcceptancePendingMail::class);
});

test('it does not send notifications when there are no pending manuscripts', function (): void {
    Mail::fake();

    $user = User::factory()->create();
    ManuscriptRecord::factory()->create([
        'user_id' => $user->id,
        'status' => ManuscriptRecordStatus::ACCEPTED,
        'reviewed_at' => now()->subMonths(2),
        'accepted_on' => now()->subWeek(),
    ]);

    $this->artisan('osp:send-pending-journal-acceptance-notifications')
        ->expectsOutput('Checking for manuscripts that need status updates...')
        ->expectsOutput('Manuscript status update reminder notifications have been queued.')
        ->expectsOutput('No users to notify.')
        ->assertExitCode(0);

    Mail::assertNothingQueued();
});

test('it logs activity when command is executed', function (): void {
    Mail::fake();

    $user = User::factory()->create();
    ManuscriptRecord::factory()->create([
        'user_id' => $user->id,
        'status' => ManuscriptRecordStatus::SUBMITTED,
        'reviewed_at' => now()->subMonths(1),
        'submitted_to_journal_on' => now()->subWeeks(2),
    ]);

    $this->artisan('osp:send-pending-journal-acceptance-notifications')
        ->assertExitCode(0);

    $this->assertDatabaseHas('activity_log', [
        'description' => 'SendPendingJournalAcceptanceNotifications completed successfully',
    ]);
});
