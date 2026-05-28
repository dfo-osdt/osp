<?php

namespace App\Console\Commands;

use App\Actions\ROR\DownloadLatestRORData;
use App\Actions\ROR\SyncRORData;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Description('Download and Synchronize ROR Organizations with latest data dump')]
#[Signature('osp:update-ror-organizations')]
class UpdateROROrganizations extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Downloading or looking for ROR data dump...');
        $rorPath = DownloadLatestRORData::handle(function (string|iterable $message): void {
            $this->output->write($message);
        });
        if (! $rorPath) {
            $this->error('Unable to download ROR data dump');

            return;
        }
        $this->info('Found and uncompressed here: '.$rorPath['jsonFile']);
        $this->info('Synchronizing ROR data with Organizations...');
        $progressBar = $this->output->createProgressBar(100);
        SyncRORData::handle(
            $rorPath['jsonFile'],
            $rorPath['version'],
            function (int $percentage) use ($progressBar): void {
                $progressBar->setProgress($percentage);
            }
        );

        $progressBar->finish();
        $this->info("\nDone!");

    }
}
