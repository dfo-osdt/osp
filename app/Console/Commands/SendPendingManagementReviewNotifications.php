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
    protected $signature = 'osp:send-pending-management-review-notifications {--force : Force execution even on non-business days}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weekly email notifications for pending management reviews (only runs on Mondays or next business day)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (! now()->isMonday() && ! $this->option('force')) {
            $this->info('Skipping notification - today is not Monday.');
            activity()
                ->withProperties([
                    'date' => now()->toDateString(),
                    'is_monday' => false,
                ])
                ->log('SendPendingManagementReviewNotifications skipped - not Monday');

            return 0;
        }

        if (! now()->isBusinessDay() && ! $this->option('force')) {
            $this->info('Skipping notification - today is not a business day.');
            activity()
                ->withProperties([
                    'date' => now()->toDateString(),
                    'is_business_day' => false,
                ])
                ->log('SendPendingManagementReviewNotifications skipped - not a business day');

            return 0;
        }

        if ((! now()->isMonday() || ! now()->isBusinessDay()) && $this->option('force')) {
            $this->warn('Forcing execution...');
            activity()
                ->withProperties([
                    'date' => now()->toDateString(),
                    'is_monday' => now()->isMonday(),
                    'is_business_day' => now()->isBusinessDay(),
                    'forced' => true,
                ])
                ->log('SendPendingManagementReviewNotifications forced');
        }

        $this->info('Checking for pending management reviews...');

        CheckPendingManagementReviews::handle();

        $this->info('Pending management review notifications have been queued.');
        activity()
            ->withProperties([
                'date' => now()->toDateString(),
                'is_monday' => now()->isMonday(),
                'is_business_day' => now()->isBusinessDay(),
            ])
            ->log('SendPendingManagementReviewNotifications completed successfully');

        return 0;
    }
}
