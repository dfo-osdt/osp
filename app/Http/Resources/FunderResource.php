<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource for Funder model.
 * @extends JsonResource<\App\Models\Funder>
 * @mixin \App\Models\Funder
 */
class FunderResource extends JsonResource
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
                'name_en' => $this->name_en,
                'name_fr' => $this->name_fr,
                'organization_id' => $this->organization_id,
                // relationships
                'organization' => new OrganizationResource($this->whenLoaded('organization')),
            ],
            'can' => [
                'update' => false,
                'delete' => false,
            ],
        ];
    }
}
