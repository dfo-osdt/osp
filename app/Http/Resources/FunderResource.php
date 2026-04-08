<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Funder;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Funder
 */
class FunderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|\JsonSerializable
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
