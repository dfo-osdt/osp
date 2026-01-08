<?php

namespace App\Actions\Notifications;

use App\Mail\JournalAcceptancePendingMail;
use App\Models\ManuscriptRecord;
use App\Models\Publication;
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
        $manuscripts = ManuscriptRecord::pendingJournalAcceptance()
            ->with(['user'])
            ->get();

        // Get all publications that have been accepted but not yet published
        $publications = Publication::pendingPublish()
            ->with(['user', 'journal'])
            ->get();

        // Group by user ID and combine manuscripts and publications
        $notificationsPerUser = collect();

        foreach ($manuscripts as $manuscript) {
            $userId = $manuscript->user_id;
            if (! $notificationsPerUser->has($userId)) {
                $notificationsPerUser[$userId] = [
                    'user' => $manuscript->user,
                    'manuscripts' => collect(),
                    'publications' => collect(),
                ];
            }
            $notificationsPerUser[$userId]['manuscripts']->push($manuscript);
        }

        foreach ($publications as $publication) {
            $userId = $publication->user_id;
            if (! $notificationsPerUser->has($userId)) {
                $notificationsPerUser[$userId] = [
                    'user' => $publication->user,
                    'manuscripts' => collect(),
                    'publications' => collect(),
                ];
            }
            $notificationsPerUser[$userId]['publications']->push($publication);
        }

        $notifiedUsers = [];

        // Send email to each user with pending manuscripts and/or publications
        foreach ($notificationsPerUser as $data) {
            $user = $data['user'];
            Mail::queue(new JournalAcceptancePendingMail(
                $data['manuscripts'],
                $data['publications'],
                $user
            ));
            Log::info("Queued journal acceptance pending email for user: {$user->first_name} {$user->last_name}");
            $notifiedUsers[] = $user;
        }

        return $notifiedUsers;
    }
}
