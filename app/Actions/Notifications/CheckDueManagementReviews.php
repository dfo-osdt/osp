<?php

namespace App\Actions\Notifications;

use App\Mail\ManagementReviewDueMail;
use App\Models\ManagementReviewStep;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class CheckDueManagementReviews
{
    public static function handle(): void
    {
        $overdueReviews = ManagementReviewStep::query()
            ->overdue()
            ->with(['manuscriptRecord', 'user'])
            ->get();

        $dueSoonReviews = ManagementReviewStep::query()
            ->dueSoon()
            ->with(['manuscriptRecord', 'user'])
            ->get();

        $reviews = $overdueReviews->merge($dueSoonReviews)->unique('id');

        // now we want to group per user, only one notification per user
        $reviewsPerUser = $reviews->groupBy('user_id');

        foreach ($reviewsPerUser as $userId => $userReviews) {

            $user = User::findOrFail($userId);
            Mail::queue(new ManagementReviewDueMail($userReviews, $user));

        }
    }
}
