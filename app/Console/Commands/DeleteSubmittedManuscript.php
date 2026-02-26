<?php

namespace App\Console\Commands;

use App\Actions\DeleteSubmittedManuscriptRecord;
use App\Enums\ManuscriptRecordStatus;
use App\Models\ManuscriptRecord;
use Illuminate\Console\Command;

class DeleteSubmittedManuscript extends Command
{
    protected $signature = 'osp:delete-submitted-manuscript {id : The manuscript record ID} {--force : Skip confirmation}';

    protected $description = 'Permanently delete a submitted manuscript record and all its associated data';

    public function handle(): int
    {
        $manuscriptRecord = ManuscriptRecord::withTrashed()->find($this->argument('id'));

        if (! $manuscriptRecord) {
            $this->error('Manuscript record not found.');

            return 1;
        }

        if ($manuscriptRecord->status !== ManuscriptRecordStatus::IN_REVIEW) {
            $this->error("Manuscript record is not in IN_REVIEW status. Current status: {$manuscriptRecord->status->value}");

            return 1;
        }

        $this->table(
            headers: ['ID', 'ULID', 'Title', 'Status', 'Author', 'Submitted At'],
            rows: [[
                $manuscriptRecord->id,
                $manuscriptRecord->ulid,
                $manuscriptRecord->title,
                $manuscriptRecord->status->value,
                $manuscriptRecord->user->email,
                $manuscriptRecord->sent_for_review_at,
            ]]
        );

        if (! $this->option('force') && ! $this->confirm('Are you sure you want to permanently delete this manuscript record? This action cannot be undone.')) {
            $this->info('Operation cancelled.');

            return 0;
        }

        DeleteSubmittedManuscriptRecord::handle($manuscriptRecord);

        activity()
            ->withProperties([
                'date' => now()->toDateString(),
                'manuscript_record_id' => $manuscriptRecord->id,
                'manuscript_record_ulid' => $manuscriptRecord->ulid,
                'manuscript_record_title' => $manuscriptRecord->title,
            ])
            ->log('DeleteSubmittedManuscript command executed successfully');

        $this->info('Manuscript record and all associated data have been permanently deleted.');

        return 0;
    }
}
