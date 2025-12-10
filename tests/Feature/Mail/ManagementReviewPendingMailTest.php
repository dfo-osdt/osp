<?php

use App\Mail\ManagementReviewPendingMail;
use App\Models\ManagementReviewStep;
use App\Models\User;
use Illuminate\Support\Collection;

test('mail renders with reviews that have decision_expected_by dates', function (): void {
    $user = User::factory()->create();
    $reviews = ManagementReviewStep::factory()->count(2)->create([
        'user_id' => $user->id,
        'decision_expected_by' => now()->addDays(5),
    ]);

    $mailable = new ManagementReviewPendingMail($reviews, $user);
    $mailable->build();

    $rendered = $mailable->render();

    expect($rendered)->toContain($user->first_name);
    expect($rendered)->toContain('Weekly Summary');
    expect($rendered)->toContain('View My Reviews');
});

test('mail renders with reviews that have null decision_expected_by dates', function (): void {
    $user = User::factory()->create();
    $reviews = ManagementReviewStep::factory()->count(2)->create([
        'user_id' => $user->id,
        'decision_expected_by' => null,
    ]);

    $mailable = new ManagementReviewPendingMail($reviews, $user);
    $mailable->build();

    $rendered = $mailable->render();

    expect($rendered)->toContain($user->first_name);
    expect($rendered)->toContain('Weekly Summary');
    expect($rendered)->toContain('N/A'); // Should show N/A for missing dates
});

test('mail renders with mixed reviews - some with dates, some without', function (): void {
    $user = User::factory()->create();

    $reviewWithDate = ManagementReviewStep::factory()->create([
        'user_id' => $user->id,
        'decision_expected_by' => now()->addDays(5),
    ]);

    $reviewWithoutDate = ManagementReviewStep::factory()->create([
        'user_id' => $user->id,
        'decision_expected_by' => null,
    ]);

    $reviews = Collection::make([$reviewWithDate, $reviewWithoutDate]);

    $mailable = new ManagementReviewPendingMail($reviews, $user);
    $mailable->build();

    $rendered = $mailable->render();

    expect($rendered)->toContain($user->first_name);
    expect($rendered)->toContain('Weekly Summary');
    expect($rendered)->toContain('N/A'); // Should show N/A for the review without a date
});
