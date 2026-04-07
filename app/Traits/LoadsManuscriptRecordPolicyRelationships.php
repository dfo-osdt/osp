<?php

declare(strict_types=1);

namespace App\Traits;

trait LoadsManuscriptRecordPolicyRelationships
{
    /**
     * Get the relationships needed for ManuscriptRecord policy authorization.
     */
    private function getManuscriptRecordPolicyRelationships(): array
    {
        return [
            'user',
            'shareables',
            'managementReviewSteps',
            'manuscriptAuthors' => [
                'author',
            ],
        ];
    }
}
