<?php

namespace App\Actions;

use App\Enums\PublicationStatus;
use App\Models\Journal;
use App\Models\ManuscriptRecord;
use App\Models\Publication;

class CreatePublicationFromManuscript
{
    public static function handle(ManuscriptRecord $manuscriptRecord, Journal $journal): Publication
    {
        // create a new publication record
        $publication = $manuscriptRecord->publication()->create([
            'title' => $manuscriptRecord->title,
            'journal_id' => $journal->id,
            'status' => PublicationStatus::ACCEPTED,
            'accepted_on' => $manuscriptRecord->accepted_on,
            'user_id' => $manuscriptRecord->user_id,
        ]);

        // attach the manuscript's authors to the publication
        $manuscriptRecord->manuscriptAuthors()->each(function ($manuscriptAuthor) use ($publication) {
            $publication->publicationAuthors()->create([
                'author_id' => $manuscriptAuthor->author_id,
                'organization_id' => $manuscriptAuthor->organization_id,
                'is_corresponding_author' => $manuscriptAuthor->is_corresponding_author,
            ]);
        });

        // attach the manuscript's fundingSources to the publication
        $manuscriptRecord->fundingSources()->each(function ($fundingSource) use ($publication) {
            $publication->fundingSources()->create([
                'funder_id' => $fundingSource->funder_id,
                'title' => $fundingSource->title,
                'description' => $fundingSource->description,
            ]);
        });

        return $publication;
    }
}
