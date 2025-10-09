<?php

namespace App\Console\Commands;

use App\Actions\Notifications\CheckDueManagementReviews;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendDueManagementReviewNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'osp:send-due-management-review-notifications {--force : Force execution even on non-business days}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email notifications for due and overdue management reviews (only runs on business days)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (! now()->isBusinessDay() && ! $this->option('force')) {
            $this->info('Skipping notification - today is not a business day.');
            Log::info('SendDueManagementReviewNotifications skipped - not a business day');

            return 0;
        }

        if (! now()->isBusinessDay() && $this->option('force')) {
            $this->warn('Forcing execution on a non-business day...');
        }

        $this->info('Checking for due and overdue management reviews...');

        CheckDueManagementReviews::handle();

        $this->info('Management review notifications have been queued.');
        Log::info('SendDueManagementReviewNotifications completed successfully');

        return 0;
    }
}
