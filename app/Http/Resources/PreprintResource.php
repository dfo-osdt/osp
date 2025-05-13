<?php

namespace App\Http\Resources;

use App\Models\ManuscriptAuthor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\ManuscriptRecord
 */
class PreprintResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => [
                'manuscript_record_id' => $this->id,
                'title' => $this->title,
                'region_id' => $this->region_id,
                'preprint_url' => $this->preprint_url,
                'accepted_on' => $this->accepted_on,
                // relationships
                'authors' => ManuscriptAuthorResource::collection($this->whenLoaded('manuscriptAuthors')),
                'user' => UserResource::make($this->whenLoaded('user'))
            ],
            'can' => [
                'udpate' => false,
                'delete' => false
            ]
        ];
    }
}
