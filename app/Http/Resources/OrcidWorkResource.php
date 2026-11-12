<?php

namespace App\Http\Resources;

use App\Enums\OrcidSyncIntent;
use App\Models\PublicationAuthor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A publication author as listed to the author for their ORCID works.
 *
 * @mixin PublicationAuthor
 */
class OrcidWorkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $publication = $this->publication;

        return [
            'data' => [
                'id' => $this->id,
                'orcid_status' => match (true) {
                    // still on ORCID until the delete runs, e.g. once the author reconnects
                    $this->needsRemovalFromOrcid() => 'removing',
                    $this->orcid_sync === OrcidSyncIntent::DECLINED => 'declined',
                    ! $this->isWantedOnOrcid() => 'not_on_orcid',
                    $this->isSyncedWithOrcid() => 'on_orcid',
                    default => 'pending',
                },
                'publication' => [
                    'id' => $publication->id,
                    'title' => $publication->title,
                    'doi' => $publication->doi,
                    'published_on' => $publication->published_on?->toDateString(),
                    'journal' => JournalResource::make($publication->journal),
                ],
            ],
        ];
    }
}
