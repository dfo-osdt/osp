<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ManuscriptRecordResource extends JsonResource
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
                'type' => $this->type,
                'status' => $this->status,
                'title' => $this->title,
                'region_id' => $this->region_id,
                'user_id' => $this->user_id,

                // text fields / send empty string if null.
                'abstract' => $this->abstract ?? '',
                'pls_en' => $this->pls_en ?? '',
                'pls_fr' => $this->pls_fr ?? '',
                'scientific_implications' => $this->scientific_implications ?? '',
                'regions_and_species' => $this->regions_and_species ?? '',
                'relevant_to' => $this->relevant_to ?? '',
                'additional_information' => $this->additional_information ?? '',

                // dates and times
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'sent_for_review_at' => $this->sent_for_review_at,
                'reviewed_at' => $this->reviewed_at,
                'submitted_to_journal_on' => $this->submitted_to_journal_on,
                'accepted_on' => $this->accepted_on,
                'withdrawn_on' => $this->withdrawn_on,
                //relationships - if loaded
                'region' => RegionResource::make($this->whenLoaded('region')),
                'manuscriptAuthors' => ManuscriptAuthorResource::collection($this->whenLoaded('manuscriptAuthors')),
            ],
            'can' => [
                'update' => auth()->user()->can('update', $this->resource),
                'delete' => auth()->user()->can('delete', $this->resource),
            ],
        ];
    }
}
