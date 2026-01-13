<?php

namespace App\Console\Commands;

use App\Models\Publication;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RestoreSoftDeletedPublication extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'osp:restore-soft-deleted-publication {id : The ID of the publication to restore}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore a soft-deleted publication and its related data';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $id = $this->argument('id');

        $this->info("Finding soft-deleted publication with ID: {$id}...");

        $publication = Publication::onlyTrashed()->find($id);

        if (! $publication) {
            $this->error("No soft-deleted publication found with ID: {$id}");

            return 1;
        }

        // Show publication details
        $this->table(
            headers: ['ID', 'Title', 'Journal ID', 'Deleted At'],
            rows: [[
                $publication->id,
                $publication->title,
                $publication->journal_id,
                $publication->deleted_at->format('Y-m-d H:i:s'),
            ]]
        );

        if (! $this->confirm('Do you want to restore this publication?')) {
            $this->info('Operation cancelled.');

            return 0;
        }

        DB::transaction(function () use ($publication) {
            // Restore the publication
            $publication->restore();

            // Restore associated soft-deleted publication authors
            $publication->publicationAuthors()->withTrashed()->restore();

            // Restore associated soft-deleted funding sources
            $publication->fundingSources()->withTrashed()->restore();
        });

        activity()
            ->withProperties([
                'date' => now()->toDateString(),
                'publication_id' => $publication->id,
                'publication_title' => $publication->title,
            ])
            ->log('RestoreSoftDeletedPublication completed successfully');

        $this->info("Successfully restored publication ID: {$publication->id}");

        return 0;
    }
}
