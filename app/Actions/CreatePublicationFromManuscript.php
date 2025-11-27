<?php

namespace App\Actions;

use App\Enums\PublicationStatus;
use App\Enums\SupplementaryFileType;
use App\Models\Journal;
use App\Models\ManuscriptRecord;
use App\Models\Publication;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CreatePublicationFromManuscript
{
    public static function handle(ManuscriptRecord $manuscriptRecord, Journal $journal, string|UploadedFile|null $file = null): Publication
    {

        // create a new publication record
        return DB::transaction(function () use ($manuscriptRecord, $journal, $file) {
            $publication = $manuscriptRecord->publication()->create([
                'title' => $manuscriptRecord->title,
                'journal_id' => $journal->id,
                'status' => PublicationStatus::ACCEPTED,
                'accepted_on' => $manuscriptRecord->accepted_on,
                'user_id' => $manuscriptRecord->user_id,
                'region_id' => $manuscriptRecord->region_id,
            ]);

            // attach the manuscript's authors to the publication
            $manuscriptRecord->manuscriptAuthors()->each(function ($manuscriptAuthor) use ($publication): void {
                $publication->publicationAuthors()->create([
                    'author_id' => $manuscriptAuthor->author_id,
                    'organization_id' => $manuscriptAuthor->organization_id,
                    'is_corresponding_author' => $manuscriptAuthor->is_corresponding_author,
                ]);
            });

            // attach the manuscript's fundingSources to the publication
            $manuscriptRecord->fundingSources()->each(function ($fundingSource) use ($publication): void {
                $publication->fundingSources()->create([
                    'funder_id' => $fundingSource->funder_id,
                    'title' => $fundingSource->title,
                    'description' => $fundingSource->description,
                ]);
            });

            if ($file) {

                if (is_string($file) && (!file_exists($file) || ! is_readable($file))) {
                    throw new \Exception("The file path provided does not exist or is not readable: $file");
                }

                $publication->addSupplementaryFile($file,
                    SupplementaryFileType::AUTHORS_ACCEPTED_MANUSCRIPT,
                    "Submission to Single Window for Science Publications / Soumission à l'équipe de guichet unique pour les publications scientifiques"
                );
            }

            return $publication;
        });
    }
}
