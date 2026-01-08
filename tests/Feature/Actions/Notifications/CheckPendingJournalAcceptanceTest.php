<?php

use App\Actions\Notifications\CheckPendingJournalAcceptance;
use App\Enums\ManuscriptRecordStatus;
use App\Mail\JournalAcceptancePendingMail;
use App\Models\ManuscriptRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

test('it sends email for manuscripts with reviewed status', function (): void {
    Mail::fake();

    $user = User::factory()->create();
    ManuscriptRecord::factory()->create([
        'user_id' => $user->id,
        'status' => ManuscriptRecordStatus::REVIEWED,
        'reviewed_at' => now()->subMonths(2),
    ]);

    CheckPendingJournalAcceptance::handle();

    Mail::assertQueued(JournalAcceptancePendingMail::class, fn ($mail): bool => $mail->user->id === $user->id
        && count($mail->manuscriptIds) === 1);
});

test('it sends email for manuscripts with submitted status', function (): void {
    Mail::fake();

    $user = User::factory()->create();
    ManuscriptRecord::factory()->create([
        'user_id' => $user->id,
        'status' => ManuscriptRecordStatus::SUBMITTED,
        'reviewed_at' => now()->subMonths(1),
        'submitted_to_journal_on' => now()->subWeeks(2),
    ]);

    CheckPendingJournalAcceptance::handle();

    Mail::assertQueued(JournalAcceptancePendingMail::class, 1);
});

test('it does not include manuscripts that have been accepted', function (): void {
    Mail::fake();

    $user = User::factory()->create();
    ManuscriptRecord::factory()->create([
        'user_id' => $user->id,
        'status' => ManuscriptRecordStatus::ACCEPTED,
        'reviewed_at' => now()->subMonths(2),
        'accepted_on' => now()->subWeeks(1),
    ]);

    CheckPendingJournalAcceptance::handle();

    Mail::assertNothingQueued();
});

test('it does not include manuscripts that have been withdrawn', function (): void {
    Mail::fake();

    $user = User::factory()->create();
    ManuscriptRecord::factory()->create([
        'user_id' => $user->id,
        'status' => ManuscriptRecordStatus::WITHDRAWN,
        'reviewed_at' => now()->subMonths(2),
        'withdrawn_on' => now()->subWeeks(1),
    ]);

    CheckPendingJournalAcceptance::handle();

    Mail::assertNothingQueued();
});

test('it does not include manuscripts in draft status', function (): void {
    Mail::fake();

    $user = User::factory()->create();
    ManuscriptRecord::factory()->create([
        'user_id' => $user->id,
        'status' => ManuscriptRecordStatus::DRAFT,
    ]);

    CheckPendingJournalAcceptance::handle();

    Mail::assertNothingQueued();
});

test('it does not include manuscripts in in_review status', function (): void {
    Mail::fake();

    $user = User::factory()->create();
    ManuscriptRecord::factory()->create([
        'user_id' => $user->id,
        'status' => ManuscriptRecordStatus::IN_REVIEW,
    ]);

    CheckPendingJournalAcceptance::handle();

    Mail::assertNothingQueued();
});

test('it groups multiple manuscripts by user and sends one email per user', function (): void {
    Mail::fake();

    $user = User::factory()->create();

    ManuscriptRecord::factory()->create([
        'user_id' => $user->id,
        'status' => ManuscriptRecordStatus::REVIEWED,
        'reviewed_at' => now()->subMonths(3),
    ]);

    ManuscriptRecord::factory()->create([
        'user_id' => $user->id,
        'status' => ManuscriptRecordStatus::SUBMITTED,
        'reviewed_at' => now()->subMonths(1),
        'submitted_to_journal_on' => now()->subWeeks(3),
    ]);

    CheckPendingJournalAcceptance::handle();

    Mail::assertQueued(JournalAcceptancePendingMail::class, 1);
    Mail::assertQueued(JournalAcceptancePendingMail::class, fn ($mail): bool => $mail->user->id === $user->id
        && count($mail->manuscriptIds) === 2);
});

test('it sends separate emails to different users', function (): void {
    Mail::fake();

    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    ManuscriptRecord::factory()->create([
        'user_id' => $user1->id,
        'status' => ManuscriptRecordStatus::REVIEWED,
        'reviewed_at' => now()->subMonths(2),
    ]);

    ManuscriptRecord::factory()->create([
        'user_id' => $user2->id,
        'status' => ManuscriptRecordStatus::SUBMITTED,
        'reviewed_at' => now()->subMonths(1),
        'submitted_to_journal_on' => now()->subWeeks(2),
    ]);

    CheckPendingJournalAcceptance::handle();

    Mail::assertQueued(JournalAcceptancePendingMail::class, 2);
    Mail::assertQueued(JournalAcceptancePendingMail::class, fn ($mail): bool => $mail->user->id === $user1->id
        && count($mail->manuscriptIds) === 1);
    Mail::assertQueued(JournalAcceptancePendingMail::class, fn ($mail): bool => $mail->user->id === $user2->id
        && count($mail->manuscriptIds) === 1);
});

test('it does not send email when there are no pending manuscripts', function (): void {
    Mail::fake();

    CheckPendingJournalAcceptance::handle();

    Mail::assertNothingQueued();
});
