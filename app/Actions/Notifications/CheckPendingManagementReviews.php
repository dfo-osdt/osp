<?php

namespace App\Actions\Notifications;

use App\Mail\ManagementReviewPendingMail;
use App\Models\ManagementReviewStep;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckPendingManagementReviews
{
    /**
     * @return array<int, User>
     */
    public static function handle(): array
    {
        // Get all pending reviews grouped by user
        $reviewsPerUser = ManagementReviewStep::pending()
            ->with(['manuscriptRecord', 'user'])
            ->get()
            ->groupBy('user_id');

        $notifiedUsers = [];

        // For each user, check if they have at least one review pending for 4+ business days
        foreach ($reviewsPerUser as $userId => $userReviews) {
            $hasOldReview = $userReviews->some(fn ($review): bool => $review->created_at <= now()->subBusinessDays(4));

            // Only send email if user has at least one review that's 4+ business days old
            if ($hasOldReview) {
                $user = \App\Models\User::query()->findOrFail($userId);
                Mail::queue(new ManagementReviewPendingMail($userReviews, $user));
                Log::info("Queued management review pending email for user: {$user->full_name}");
                $notifiedUsers[] = $user;
            }
        }

        return $notifiedUsers;

    }
}
