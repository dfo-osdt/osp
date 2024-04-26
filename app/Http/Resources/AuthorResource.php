<?php

namespace App\Http\Resources;

use Auth;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource for Author model.
 *
 * @extends JsonResource<\App\Models\Author>
 * @mixin \App\Models\Author
 */
class AuthorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'data' => [
                'id' => $this->id,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'orcid' => $this->orcid ?? '',
                'orcid_verified' => $this->orcid_verified ?? false,
                'orcid_connected' => $this->orcid_access_token ? true : false,
                'email' => $this->email,
                'user_id' => $this->user_id,
                'organization_id' => $this->organization_id,
                'organization' => OrganizationResource::make($this->whenLoaded('organization')),
                'expertises' => ExpertiseResource::collection($this->whenLoaded('expertises')),
            ],
            'can' => [
                'update' => Auth::user()->can('update', $this->resource),
                'delete' => Auth::user()->can('delete', $this->resource),
            ],
        ];
    }
}
