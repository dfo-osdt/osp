<?php

namespace App\Http\Resources;

use Auth;
use Illuminate\Http\Resources\Json\JsonResource;

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
                'doi' => $this->doi,
                'accepted_on' => $this->accepted_on,
                'published_on' => $this->published_on,
                'embargoed_until' => $this->embargoed_until,
                'manuscript_record_id' => $this->manuscript_record_id,
                'journal_id' => $this->journal_id,
                'user_id' => $this->user_id,
                'manuscript_record' => ManuscriptRecordResource::make($this->whenLoaded('manuscriptRecord')),
                'journal' => JournalResource::make($this->whenLoaded('journal')),
                'user' => UserResource::make($this->whenLoaded('user')),
            ],
            'can' => [
                'update' => Auth::user()->can('update', $this->resource),
                'delete' => Auth::user()->can('delete', $this->resource),
            ],
        ];
    }
}
