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

        CheckPendingManagementReviews::handle();

        $this->info('Pending management review notifications have been queued.');
        activity()
            ->withProperties([
                'date' => now()->toDateString(),
            ])
            ->log('SendPendingManagementReviewNotifications completed successfully');

        return 0;
    }
}
