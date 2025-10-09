<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Spatie\Health\Commands\RunHealthChecksCommand;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        // prune the telescope database
        $schedule->command('telescope:prune --hours=48')->daily()->at('00:30');

        // prune the activity log - keep 1 year of data
        $schedule->command('activitylog:clean --days=365')->daily()->at('01:00');

        // backup the database
        $schedule->command('backup:clean')->daily()->at('01:00');
        $schedule->command('backup:run --only-db')->daily()->at('02:00');

        // health checks
        $schedule->command(RunHealthChecksCommand::class)->everyMinute();
        $schedule->command('model:prune', [
            '--model' => [
                \Spatie\Health\Models\HealthCheckResultHistoryItem::class,
            ],
        ])->daily();

        // send management review notifications (only on business days)
        $schedule->command('osp:send-due-management-review-notifications')->daily()->at('14:00'); // 9am EST
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
