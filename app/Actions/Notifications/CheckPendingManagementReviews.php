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
        // Get all reviews pending for at least 4 business days
        $oldPendingReviews = ManagementReviewStep::pendingForDays(4)
            ->with(['manuscriptRecord', 'user'])
            ->get();

        // Get unique user IDs who have at least one old pending review
        $userIds = $oldPendingReviews->pluck('user_id')->unique();

        // For each user, get ALL their pending reviews (not just old ones)
        foreach ($userIds as $userId) {
            $user = User::findOrFail($userId);

            $allPendingReviews = ManagementReviewStep::pending()
                ->where('user_id', $userId)
                ->with(['manuscriptRecord', 'user'])
                ->get();

            Mail::queue(new ManagementReviewPendingMail($allPendingReviews, $user));
        }
    }
}
