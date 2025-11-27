<?php

namespace App\Console\Commands;

use App\Actions\ROR\DownloadLatestRORData;
use App\Actions\ROR\SyncRORData;
use Illuminate\Console\Command;

class UpdateROROrganizations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'osp:update-ror-organizations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download and Synchronize ROR Organizations with latest data dump';

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
