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
    public function handle()
    {
        $this->info("Downloading or looking for ROR data dump...");
        $path = DownloadLatestRORData::handle(function ($message) {
            $this->output->write($message);
        });
        if(!$path){
            $this->error("Unable to download ROR data dump");
            return;
        }
        $this->info("Found and uncompressed here: " . $path);
        $this->info("Synchronizing ROR data with Organizations...");
        $progressBar = $this->output->createProgressBar(100);
        SyncRORData::handle(
            $path,
            function ($percentage) use ($progressBar) {
                $progressBar->setProgress($percentage);
            }
        );

        $progressBar->finish();
        $this->info("\nDone!");

    }
}
