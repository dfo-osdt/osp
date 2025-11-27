<?php

use App\Actions\Notifications\CheckPendingManagementReviews;
use App\Mail\ManagementReviewPendingMail;
use App\Models\ManagementReviewStep;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

test('it sends email for pending reviews older than 4 business days', function (): void {
    Mail::fake();

    $user = User::factory()->create();
    ManagementReviewStep::factory()->create([
        'user_id' => $user->id,
        'created_at' => now()->subBusinessDays(5),
    ]);

    CheckPendingManagementReviews::handle();

    Mail::assertQueued(ManagementReviewPendingMail::class, fn ($mail): bool => $mail->user->id === $user->id
        && $mail->reviews->count() === 1);
});

test('it does not send email for reviews pending less than 4 business days', function (): void {
    Mail::fake();

    $user = User::factory()->create();
    ManagementReviewStep::factory()->create([
        'user_id' => $user->id,
        'created_at' => now()->subBusinessDays(3),
    ]);

    CheckPendingManagementReviews::handle();

    Mail::assertNothingQueued();
});

test('it includes all pending reviews when user has at least one old review', function (): void {
    Mail::fake();

    $user = User::factory()->create();

    // Old review (should trigger email)
    ManagementReviewStep::factory()->create([
        'user_id' => $user->id,
        'created_at' => now()->subBusinessDays(5),
    ]);

    // Recent reviews (should be included in email)
    ManagementReviewStep::factory()->create([
        'user_id' => $user->id,
        'created_at' => now()->subBusinessDays(2),
    ]);
    ManagementReviewStep::factory()->create([
        'user_id' => $user->id,
        'created_at' => now()->subDay(),
    ]);

    CheckPendingManagementReviews::handle();

    Mail::assertQueued(ManagementReviewPendingMail::class, 1);
    Mail::assertQueued(ManagementReviewPendingMail::class, fn ($mail): bool => $mail->user->id === $user->id
        && $mail->reviews->count() === 3);
});

test('it sends separate emails to different users', function (): void {
    Mail::fake();

    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    ManagementReviewStep::factory()->create([
        'user_id' => $user1->id,
        'created_at' => now()->subBusinessDays(5),
    ]);
    ManagementReviewStep::factory()->create([
        'user_id' => $user2->id,
        'created_at' => now()->subBusinessDays(6),
    ]);

    CheckPendingManagementReviews::handle();

    Mail::assertQueued(ManagementReviewPendingMail::class, 2);
    Mail::assertQueued(ManagementReviewPendingMail::class, fn ($mail): bool => $mail->user->id === $user1->id
        && $mail->reviews->count() === 1);
    Mail::assertQueued(ManagementReviewPendingMail::class, fn ($mail): bool => $mail->user->id === $user2->id
        && $mail->reviews->count() === 1);
});

test('it does not send emails when there are no old pending reviews', function (): void {
    Mail::fake();

    ManagementReviewStep::factory()->create([
        'created_at' => now()->subBusinessDays(1),
    ]);

    CheckPendingManagementReviews::handle();

    Mail::assertNothingQueued();
});

test('it does not include completed reviews', function (): void {
    Mail::fake();

    $user = User::factory()->create();

    // Old pending review
    ManagementReviewStep::factory()->create([
        'user_id' => $user->id,
        'created_at' => now()->subBusinessDays(5),
    ]);

    // Completed review (should not be included)
    ManagementReviewStep::factory()->approved()->create([
        'user_id' => $user->id,
        'created_at' => now()->subBusinessDays(5),
    ]);

    CheckPendingManagementReviews::handle();

    Mail::assertQueued(ManagementReviewPendingMail::class, 1);
    Mail::assertQueued(ManagementReviewPendingMail::class, fn ($mail): bool => $mail->user->id === $user->id
        && $mail->reviews->count() === 1);
});

test('it sends one email per user with all their pending reviews', function (): void {
    Mail::fake();

    $user = User::factory()->create();

    // Multiple old reviews
    ManagementReviewStep::factory()->create([
        'user_id' => $user->id,
        'created_at' => now()->subBusinessDays(10),
    ]);
    ManagementReviewStep::factory()->create([
        'user_id' => $user->id,
        'created_at' => now()->subBusinessDays(7),
    ]);
    ManagementReviewStep::factory()->create([
        'user_id' => $user->id,
        'created_at' => now()->subBusinessDays(4),
    ]);

    CheckPendingManagementReviews::handle();

    Mail::assertQueued(ManagementReviewPendingMail::class, 1);
    Mail::assertQueued(ManagementReviewPendingMail::class, fn ($mail): bool => $mail->user->id === $user->id
        && $mail->reviews->count() === 3);
});
