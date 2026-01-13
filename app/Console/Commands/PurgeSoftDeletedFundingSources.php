<?php

namespace App\Console\Commands;

use App\Models\FundingSource;
use Illuminate\Console\Command;

class PurgeSoftDeletedFundingSources extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'osp:purge-soft-deleted-funding-sources {--force : Skip confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permanently delete soft-deleted funding sources from the database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Finding soft-deleted funding sources...');

        $fundingSources = FundingSource::onlyTrashed()->get();

        if ($fundingSources->isEmpty()) {
            $this->info('No soft-deleted funding sources found.');

            return 0;
        }

        $this->table(
            headers: ['ID', 'Title', 'Fundable Type', 'Fundable ID', 'Deleted At'],
            rows: $fundingSources->map(fn ($fundingSource) => [
                $fundingSource->id,
                $fundingSource->title,
                class_basename($fundingSource->fundable_type),
                $fundingSource->fundable_id,
                $fundingSource->deleted_at->format('Y-m-d H:i:s'),
            ])->all()
        );

        if (! $this->option('force')) {
            if (! $this->confirm("Are you sure you want to permanently delete {$fundingSources->count()} funding source(s)?")) {
                $this->info('Operation cancelled.');

                return 0;
            }
        }

        $count = 0;
        foreach ($fundingSources as $fundingSource) {
            $fundingSource->forceDelete();
            $count++;
        }

        activity()
            ->withProperties([
                'date' => now()->toDateString(),
                'funding_sources_purged' => $count,
            ])
            ->log('PurgeSoftDeletedFundingSources completed successfully');

        $this->info("Successfully purged {$count} funding source(s).");

        return 0;
    }
}
