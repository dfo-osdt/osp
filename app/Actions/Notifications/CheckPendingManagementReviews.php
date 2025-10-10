<?php

namespace App\Actions\Notifications;

use App\Mail\ManagementReviewPendingMail;
use App\Models\ManagementReviewStep;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class CheckPendingManagementReviews
{
    public static function handle(): void
    {
        // Get all pending reviews grouped by user
        $reviewsPerUser = ManagementReviewStep::pending()
            ->with(['manuscriptRecord', 'user'])
            ->get()
            ->groupBy('user_id');

        // For each user, check if they have at least one review pending for 4+ business days
        foreach ($reviewsPerUser as $userId => $userReviews) {
            $hasOldReview = $userReviews->some(function ($review) {
                return $review->created_at <= now()->subBusinessDays(4);
            });

            // Only send email if user has at least one review that's 4+ business days old
            if ($hasOldReview) {
                $user = User::findOrFail($userId);
                Mail::queue(new ManagementReviewPendingMail($userReviews, $user));
            }
        }
    }
}
