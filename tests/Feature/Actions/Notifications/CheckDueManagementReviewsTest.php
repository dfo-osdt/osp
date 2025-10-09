<?php

use App\Actions\Notifications\CheckDueManagementReviews;
use App\Mail\ManagementReviewDueMail;
use App\Models\ManagementReviewStep;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

test('it sends email for overdue reviews', function () {
    Mail::fake();

    $user = User::factory()->create();
    ManagementReviewStep::factory()->create([
        'user_id' => $user->id,
        'decision_expected_by' => now()->subDays(3),
    ]);

    CheckDueManagementReviews::handle();

    Mail::assertQueued(ManagementReviewDueMail::class, function ($mail) use ($user) {
        return $mail->user->id === $user->id
            && $mail->reviews->count() === 1;
    });
});

test('it sends email for due soon reviews', function () {
    Mail::fake();

    $user = User::factory()->create();
    ManagementReviewStep::factory()->create([
        'user_id' => $user->id,
        'decision_expected_by' => now()->addDay(),
    ]);

    CheckDueManagementReviews::handle();

    Mail::assertQueued(ManagementReviewDueMail::class, function ($mail) use ($user) {
        return $mail->user->id === $user->id
            && $mail->reviews->count() === 1;
    });
});

test('it groups reviews by user and sends one email per user', function () {
    Mail::fake();

    $user = User::factory()->create();
    ManagementReviewStep::factory()->create([
        'user_id' => $user->id,
        'decision_expected_by' => now()->subDays(2),
    ]);
    ManagementReviewStep::factory()->create([
        'user_id' => $user->id,
        'decision_expected_by' => now()->addDay(),
    ]);

    CheckDueManagementReviews::handle();

    Mail::assertQueued(ManagementReviewDueMail::class, 1);
    Mail::assertQueued(ManagementReviewDueMail::class, function ($mail) use ($user) {
        return $mail->user->id === $user->id
            && $mail->reviews->count() === 2;
    });
});

test('it sends separate emails to different users', function () {
    Mail::fake();

    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    ManagementReviewStep::factory()->create([
        'user_id' => $user1->id,
        'decision_expected_by' => now()->subDay(),
    ]);
    ManagementReviewStep::factory()->create([
        'user_id' => $user2->id,
        'decision_expected_by' => now()->addDay(),
    ]);

    CheckDueManagementReviews::handle();

    Mail::assertQueued(ManagementReviewDueMail::class, 2);
    Mail::assertQueued(ManagementReviewDueMail::class, function ($mail) use ($user1) {
        return $mail->user->id === $user1->id
            && $mail->reviews->count() === 1;
    });
    Mail::assertQueued(ManagementReviewDueMail::class, function ($mail) use ($user2) {
        return $mail->user->id === $user2->id
            && $mail->reviews->count() === 1;
    });
});

test('it does not send emails when there are no due reviews', function () {
    Mail::fake();

    ManagementReviewStep::factory()->create([
        'decision_expected_by' => now()->addDays(10),
    ]);

    CheckDueManagementReviews::handle();

    Mail::assertNothingQueued();
});

test('it does not include completed reviews', function () {
    Mail::fake();

    $user = User::factory()->create();
    ManagementReviewStep::factory()->approved()->create([
        'user_id' => $user->id,
        'decision_expected_by' => now()->subDays(5),
    ]);

    CheckDueManagementReviews::handle();

    Mail::assertNothingQueued();
});

test('it includes both overdue and due soon reviews for a user', function () {
    Mail::fake();

    $user = User::factory()->create();
    ManagementReviewStep::factory()->create([
        'user_id' => $user->id,
        'decision_expected_by' => now()->subDays(3),
    ]);
    ManagementReviewStep::factory()->create([
        'user_id' => $user->id,
        'decision_expected_by' => now()->subDay(),
    ]);
    ManagementReviewStep::factory()->create([
        'user_id' => $user->id,
        'decision_expected_by' => now()->addDay(),
    ]);

    CheckDueManagementReviews::handle();

    Mail::assertQueued(ManagementReviewDueMail::class, 1);
    Mail::assertQueued(ManagementReviewDueMail::class, function ($mail) use ($user) {
        return $mail->user->id === $user->id
            && $mail->reviews->count() === 3;
    });
});
