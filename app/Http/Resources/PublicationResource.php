<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

/**
 * @mixin \App\Models\Publication
 */
class PublicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => [
                'id' => $this->id,
                'title' => $this->title,
                'status' => $this->status,
                'doi' => $this->doi,
                'is_open_access' => $this->is_open_access,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'accepted_on' => $this->accepted_on,
                'published_on' => $this->published_on,
                'embargoed_until' => $this->embargoed_until,
                'manuscript_record_id' => $this->manuscript_record_id,
                'journal_id' => $this->journal_id,
                'user_id' => $this->user_id,
                'region_id' => $this->region_id,
                // relationships - if loaded
                'manuscript_record' => ManuscriptRecordResource::make($this->whenLoaded('manuscriptRecord')),
                'journal' => JournalResource::make($this->whenLoaded('journal')),
                'user' => UserResource::make($this->whenLoaded('user')),
                'publication_authors' => PublicationAuthorResource::collection($this->whenLoaded('publicationAuthors')),
                'funding_sources' => FundingSourceResource::collection($this->whenLoaded('fundingSources')),
                'region' => RegionResource::make($this->whenLoaded('region')),
            ],
            'can' => [
                'update' => Gate::allows('update', $this->resource),
                'delete' => Gate::allows('delete', $this->resource),
            ],
        ];
    }
}
