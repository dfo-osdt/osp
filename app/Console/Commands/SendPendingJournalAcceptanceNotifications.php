<?php

namespace App\Console\Commands;

use App\Actions\Notifications\CheckPendingJournalAcceptance;
use Illuminate\Console\Command;

class SendPendingJournalAcceptanceNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'osp:send-pending-journal-acceptance-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send monthly reminder emails to update manuscript status for reviewed manuscripts not yet marked as accepted';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking for manuscripts that need status updates...');

        $users = collect(CheckPendingJournalAcceptance::handle());

        $this->info('Manuscript status update reminder notifications have been queued.');
        activity()
            ->withProperties([
                'date' => now()->toDateString(),
                'users_notified' => $users->pluck('email'),
            ])
            ->log('SendPendingJournalAcceptanceNotifications completed successfully');

        if ($users->isEmpty()) {
            $this->info('No users to notify.');

            return 0;
        }

        $this->table(
            headers: ['Name', 'Email'],
            rows: $users->map(fn ($user): array => [$user->first_name.' '.$user->last_name, $user->email])->all(),
        );

        return 0;
    }
}
