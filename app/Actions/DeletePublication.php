<?php

namespace App\Actions;

use App\Jobs\SyncWorkWithOrcid;
use App\Models\Publication;
use Illuminate\Support\Facades\DB;

class DeletePublication
{
    public static function handle(Publication $publication): bool
    {
        $deleted = DB::transaction(function () use ($publication) {
            // Soft delete publication authors
            $publication->publicationAuthors()->delete();

            // Soft delete funding sources
            $publication->fundingSources()->delete();

            // Media files will be handled automatically by Spatie Media Library

            // Soft delete the publication
            return $publication->delete();
        });

        // a deleted publication is no longer on ORCID; the authors' intent is kept for a restore
        SyncWorkWithOrcid::dispatchForChanged($publication->publicationAuthors()->onlyTrashed()->get());

        return $deleted;
    }
}
