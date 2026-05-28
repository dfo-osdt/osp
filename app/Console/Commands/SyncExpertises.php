<?php

namespace App\Console\Commands;

use App\Actions\Expertise\SyncExpertiseWithScience;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Description('Sync the local database with the expertise taxonomy from the profiles registry at profils-profiles.science.gc.ca')]
#[Signature('osp:sync-expertises')]
class SyncExpertises extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Syncing expertises...');

        $result = SyncExpertiseWithScience::handle(function ($message): void {
            $this->info($message);
        });

        if (! $result) {
            $this->error('Failed to sync expertises!');

            return;
        }

        $this->info('Expertises synced!');
    }
}
