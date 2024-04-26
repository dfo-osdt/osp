<?php

namespace App\Http\Resources;

use App\Enums\ManuscriptRecordStatus;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/**
 * Resource for ManuscriptRecord model.
 *
 * @extends JsonResource<\App\Models\ManuscriptRecord>
 *
 * @mixin \App\Models\ManuscriptRecord
 */
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
                'ulid' => $this->ulid,
                'type' => $this->type,
                'status' => $this->status,
                'title' => $this->title,
                'region_id' => $this->region_id,
                'user_id' => $this->user_id,
                'functional_area_id' => $this->functional_area_id,

                // text fields / send empty string if null.
                'abstract' => $this->abstract ?? '',
                'pls' => $this->pls ?? '',
                'relevant_to' => $this->relevant_to ?? '',
                'additional_information' => $this->additional_information ?? '',
                'potential_public_interest' => $this->potential_public_interest,

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
                'functional_area' => FunctionalAreaResource::make($this->whenLoaded('functionalArea')),
                'manuscript_authors' => ManuscriptAuthorResource::collection($this->whenLoaded('manuscriptAuthors')),
                'user' => UserResource::make($this->whenLoaded('user')),
                'publication' => $this->when($this->status === ManuscriptRecordStatus::ACCEPTED, PublicationResource::make($this->whenLoaded('publication'))),
                'funding_sources' => FundingSourceResource::collection($this->whenLoaded('fundingSources')),
                // if this manuscript is accepted, include the publication id
                // special model permissions
                'can_attach_manuscript' => Auth::user()->can('attachManuscript', $this->resource),
            ],
            'can' => [
                'update' => Auth::user()->can('update', $this->resource),
                'delete' => Auth::user()->can('delete', $this->resource),
            ],
        ];
    }
}
