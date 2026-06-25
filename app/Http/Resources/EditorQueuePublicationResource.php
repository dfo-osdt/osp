<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Publication;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Lightweight publication representation for the editor dashboard "due queue".
 *
 * @mixin Publication
 */
class EditorQueuePublicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $contact = null;

        if ($this->relationLoaded('publicationAuthors')) {
            $correspondingAuthor = $this->publicationAuthors
                ->firstWhere('is_corresponding_author', true)
                ?->author;

            $fallbackAuthor = $this->publicationAuthors->first()?->author;
            $contact = $correspondingAuthor ?? $fallbackAuthor;
        }

        return [
            'data' => [
                'id' => $this->id,
                'title' => $this->title,
                'catalogue_number' => $this->catalogue_number,
                'status' => $this->status,
                'accepted_on' => $this->accepted_on,
                'manuscript_record_id' => $this->manuscript_record_id,
                'in_planning_binder' => $this->planningBinderItem !== null,
                'contact_name' => $contact ? trim("{$contact->first_name} {$contact->last_name}") : null,
                'contact_email' => $contact?->email,
                // relationships - if loaded
                'journal' => JournalResource::make($this->whenLoaded('journal')),
                'region' => RegionResource::make($this->whenLoaded('region')),
            ],
        ];
    }
}
