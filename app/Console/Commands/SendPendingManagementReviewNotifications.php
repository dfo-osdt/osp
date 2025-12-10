<?php

namespace App\Console\Commands;

use App\Actions\Notifications\CheckPendingManagementReviews;
use Illuminate\Console\Command;

class SendPendingManagementReviewNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'osp:send-pending-management-review-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weekly email notifications for pending management reviews';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking for pending management reviews...');

        $users = collect(CheckPendingManagementReviews::handle());

        $this->info('Pending management review notifications have been queued.');
        activity()
            ->withProperties([
                'date' => now()->toDateString(),
                'users_notified' => $users->pluck('email'),
            ])
            ->log('SendPendingManagementReviewNotifications completed successfully');

        if ($users->isEmpty()) {
            $this->info('No users to notify.');

            return 0;
        }

        $this->table(
            headers: ['Name', 'Email'],
            rows: $users->map(fn ($user): array => [$user->full_name, $user->email])->all(),
        );

        return 0;
    }
}
