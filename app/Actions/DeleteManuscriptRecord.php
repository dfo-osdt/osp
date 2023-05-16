<?php

namespace App\Actions;

class DeleteManuscriptRecord
{
    public static function handle($manuscriptRecord): bool
    {
        // delete manuscript authors;
        $manuscriptRecord->manuscriptAuthors()->delete();

        // delete funding sources
        $manuscriptRecord->fundingSources()->delete();

        // files/manuscript will be handled by media library

        // delete manuscript record
        return $manuscriptRecord->delete();
    }
}
