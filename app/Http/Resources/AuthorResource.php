<?php

namespace App\Http\Resources;

use Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
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
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'orcid' => $this->orcid ?? '',
                'orcid_verified' => $this->orcid_verified ?? false,
                'orcid_connected' => $this->orcid_access_token ? true : false,
                'email' => $this->email,
                'user_id' => $this->user_id,
                'organization_id' => $this->organization_id,
                'organization' => OrganizationResource::make($this->whenLoaded('organization')),
            ],
            'can' => [
                'update' => Auth::user()->can('update', $this->resource),
                'delete' => Auth::user()->can('delete', $this->resource),
            ],
        ];
    }
}
