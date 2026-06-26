<?php

namespace App\Console\Commands;

use App\Actions\ROR\DownloadLatestRORData;
use App\Actions\ROR\SyncRORData;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

#[Description('Download and Synchronize ROR Organizations with latest data dump')]
#[Signature('osp:update-ror-organizations {--force : Sync even if the latest version is already imported} {--keep : Keep the downloaded and extracted temporary files}')]
class UpdateROROrganizations extends Command
{
    /**
     * Cache key holding the last successfully synced ROR data version.
     */
    public const VERSION_CACHE_KEY = 'ror.synced_version';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking for the latest ROR data version...');

        activity('ror')->log('Checking for the latest ROR data version...');

        $latest = DownloadLatestRORData::latestVersion();

        if (! $latest) {
            $this->error('Unable to determine the latest ROR data version.');

            return self::FAILURE;
        }

        $version = $latest['version'];

        if (! $this->option('force') && Cache::get(self::VERSION_CACHE_KEY) === $version) {

            $msg = "Already up to date (version {$version}). Nothing to do.";
            $this->info($msg);
            activity('ror')->log($msg);

            return self::SUCCESS;
        }

        $this->info("New ROR data version detected: {$version}");

        try {
            $this->info('Downloading ROR data dump...');
            $rorPath = DownloadLatestRORData::handle(function (string|iterable $message): void {
                $this->output->write($message);
            }, $latest);

            if (! $rorPath) {
                $msg = 'Unable to download and extract ROR data dump.';
                $this->error($msg);
                activity('ror')->log($msg);

                return self::FAILURE;
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

            Cache::forever(self::VERSION_CACHE_KEY, $version);

            $msg = "Done! Synced ROR version {$version}.";
            $this->info(PHP_EOL.$msg);
            activity('ror')->log($msg);

            return self::SUCCESS;
        } finally {
            if (! $this->option('keep')) {
                $this->cleanupTemporaryFiles();
            }
        }
    }

    /**
     * Remove the temporary ROR download and extraction artifacts.
     */
    private function cleanupTemporaryFiles(): void
    {
        if (Storage::exists('ror_data')) {
            Storage::deleteDirectory('ror_data');
            $this->info('Cleaned up temporary ROR files.');
        }
    }
}
