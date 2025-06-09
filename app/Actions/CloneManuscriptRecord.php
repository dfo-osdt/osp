<?php

// this we need to:
// 1. Clone it but allow the user to change the type (e.g. preprint to article)
// 2. Clone the authors, funding, but not the files or management reviews.

namespace App\Actions;

use App\Enums\ManuscriptRecordStatus;
use App\Enums\ManuscriptRecordType;
use App\Models\ManuscriptRecord;
use App\Models\User;

class CloneManuscriptRecord
{
    public static function handle(ManuscriptRecord $manuscriptRecord, User $user, ManuscriptRecordType $type): ManuscriptRecord
    {
        return \DB::transaction(function () use ($manuscriptRecord, $user, $type) {
            return self::cloneManuscriptRecord($manuscriptRecord, $user, $type);
        });
    }

    private static function cloneManuscriptRecord(ManuscriptRecord $manuscriptRecord, User $user, ManuscriptRecordType $type): ManuscriptRecord
    {
        // Define the attributes to be excluded from cloning
        $exceptedAttributes = [
            'id',
            'ulid',
            'user_id',
            'type',
            'status',
            'sent_for_review_at',
            'reviewed_at',
            'submitted_to_journal_on',
            'accepted_on',
            'withdrawn_on',
            'apply_ogl',
            'preprint_url',
            'pls_approved_by_author',
            'pls_translation_approved',
            'published_on',
            'created_at',
            'updated_at',
        ];

        $clone = $manuscriptRecord->replicate($exceptedAttributes);
        $clone->user_id = $user->id; // applicant will be changed to the current user
        $clone->type = $type; // Allow changing the type
        $clone->title = $manuscriptRecord->title.' (Clone)'; // Append "(Clone)" to the title
        $clone->status = ManuscriptRecordStatus::DRAFT; // Reset status to Draft
        $clone->save();

        // Clone authors and funding sources
        foreach ($manuscriptRecord->manuscriptAuthors as $author) {
            $clone->manuscriptAuthors()->create([
                'author_id' => $author->author_id,
                'organization_id' => $author->organization_id,
                'is_corresponding_author' => $author->is_corresponding_author,
            ]); // Clone authors
        }

        foreach ($manuscriptRecord->fundingSources as $fundingSource) {
            $clone->fundingSources()->create([
                'funder_id' => $fundingSource->funder_id,
                'title' => $fundingSource->title,
                'description' => $fundingSource->description,
            ]); // Clone funding sources
        }

        return $clone;
    }
}
