<?php

namespace App\Actions\Notifications;

use App\Mail\JournalAcceptancePendingMail;
use App\Models\ManuscriptRecord;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckPendingJournalAcceptance
{
    /**
     * @return array<int, User>
     */
    public static function handle(): array
    {
        // Get all manuscripts that have been reviewed but not yet accepted
        // grouped by user
        $manuscriptsPerUser = ManuscriptRecord::pendingJournalAcceptance()
            ->with(['user'])
            ->get()
            ->groupBy('user_id');

        $notifiedUsers = [];

        // Send email to each user with pending manuscripts
        foreach ($manuscriptsPerUser as $userId => $userManuscripts) {
            $user = $userManuscripts->first()->user;
            Mail::queue(new JournalAcceptancePendingMail($userManuscripts, $user));
            Log::info("Queued journal acceptance pending email for user: {$user->first_name} {$user->last_name}");
            $notifiedUsers[] = $user;
        }

        return $notifiedUsers;
    }
}
