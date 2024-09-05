<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;

class DeleteManuscriptRecord
{
    public static function handle($manuscriptRecord): bool
    {
        return DB::transaction(function () use ($manuscriptRecord) {
            // delete manuscript authors;
            $manuscriptRecord->manuscriptAuthors()->delete();

            // delete funding sources
            $manuscriptRecord->fundingSources()->delete();

            // files/manuscript will be handled by media library

            // delete manuscript record
            return $manuscriptRecord->delete();
        });
    }
}
