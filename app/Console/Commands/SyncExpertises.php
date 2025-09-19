<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncExpertises extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'osp:sync-expertises';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync the local database with the expertise taxonomy from the profiles registry at profils-profiles.science.gc.ca';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Syncing expertises...');

        $result = \App\Actions\Expertise\SyncExpertiseWithScience::handle(function ($message) {
            $this->info($message);
        });

        if (! $result) {
            $this->error('Failed to sync expertises!');

            return;
        }

        $this->info('Expertises synced!');
    }
}
