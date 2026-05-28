<?php

namespace App\Console\Commands;

use App\Models\Publication;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

#[\Illuminate\Console\Attributes\Description('Permanently delete soft-deleted publications from the database')]
#[\Illuminate\Console\Attributes\Signature('osp:purge-soft-deleted-publications {--force : Skip confirmation}')]
class PurgeSoftDeletedPublications extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Finding soft-deleted publications...');

        $publications = Publication::onlyTrashed()->get();

        if ($publications->isEmpty()) {
            $this->info('No soft-deleted publications found.');

            return 0;
        }

        $this->table(
            headers: ['ID', 'Title', 'Deleted At'],
            rows: $publications->map(fn ($pub): array => [
                $pub->id,
                $pub->title,
                $pub->deleted_at->format('Y-m-d H:i:s'),
            ])->all()
        );

        if (! $this->option('force') && ! $this->confirm("Are you sure you want to permanently delete {$publications->count()} publication(s)?")) {
            $this->info('Operation cancelled.');

            return 0;
        }

        $count = 0;
        foreach ($publications as $publication) {
            DB::transaction(function () use ($publication): void {
                // Force delete associated soft-deleted publication authors
                $publication->publicationAuthors()->withTrashed()->forceDelete();

                // Force delete associated soft-deleted funding sources
                $publication->fundingSources()->withTrashed()->forceDelete();

                // Force delete the publication itself
                $publication->forceDelete();
            });
            $count++;
        }

        activity()
            ->withProperties([
                'date' => now()->toDateString(),
                'publications_purged' => $count,
            ])
            ->log('PurgeSoftDeletedPublications completed successfully');

        $this->info("Successfully purged {$count} publication(s).");

        return 0;
    }
}
