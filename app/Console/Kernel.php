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
        $schedule->command('telescope:prune --hours=48')->daily('00:30');

        // backup the database
        $schedule->command('backup:clean')->daily()->at('01:00');
        $schedule->command('backup:run --only-db')->daily()->at('02:00');

        // health checks
        $schedule->command(RunHealthChecksCommand::class)->everyMinute();

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
