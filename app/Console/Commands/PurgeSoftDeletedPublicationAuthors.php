<?php

namespace App\Console\Commands;

use App\Models\PublicationAuthor;
use Illuminate\Console\Command;

class PurgeSoftDeletedPublicationAuthors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'osp:purge-soft-deleted-publication-authors {--force : Skip confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permanently delete soft-deleted publication authors from the database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Finding soft-deleted publication authors...');

        $publicationAuthors = PublicationAuthor::onlyTrashed()->with('author', 'publication')->get();

        if ($publicationAuthors->isEmpty()) {
            $this->info('No soft-deleted publication authors found.');

            return 0;
        }

        $this->table(
            headers: ['ID', 'Author', 'Publication ID', 'Publication Title', 'Deleted At'],
            rows: $publicationAuthors->map(fn ($pubAuthor) => [
                $pubAuthor->id,
                $pubAuthor->author->full_name ?? 'N/A',
                $pubAuthor->publication_id,
                $pubAuthor->publication->title ?? 'N/A',
                $pubAuthor->deleted_at->format('Y-m-d H:i:s'),
            ])->all()
        );

        if (! $this->option('force')) {
            if (! $this->confirm("Are you sure you want to permanently delete {$publicationAuthors->count()} publication author(s)?")) {
                $this->info('Operation cancelled.');

                return 0;
            }
        }

        $count = 0;
        foreach ($publicationAuthors as $publicationAuthor) {
            $publicationAuthor->forceDelete();
            $count++;
        }

        activity()
            ->withProperties([
                'date' => now()->toDateString(),
                'publication_authors_purged' => $count,
            ])
            ->log('PurgeSoftDeletedPublicationAuthors completed successfully');

        $this->info("Successfully purged {$count} publication author(s).");

        return 0;
    }
}
