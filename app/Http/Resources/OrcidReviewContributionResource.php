<?php

namespace App\Http\Resources;

use App\Models\ManuscriptReviewContributor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A review contribution as listed to its author for ORCID review credit.
 *
 * @mixin ManuscriptReviewContributor
 */
class OrcidReviewContributionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $publication = $this->manuscriptRecord->publication;

        return [
            'data' => [
                'id' => $this->id,
                'manuscript_record_id' => $this->manuscript_record_id,
                'role' => $this->role,
                'review_date' => $this->review_date?->toDateString(),
                'orcid_status' => match (true) {
                    ! $this->isWantedOnOrcid() => 'not_on_orcid',
                    $this->isSyncedWithOrcid() => 'on_orcid',
                    default => 'pending',
                },
                'publication' => [
                    'id' => $publication->id,
                    'title' => $publication->title,
                    'doi' => $publication->doi,
                    'journal' => JournalResource::make($publication->journal),
                ],
            ],
        ];
    }
}
