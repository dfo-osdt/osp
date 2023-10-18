<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExperienceResource extends JsonResource
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
                'id' => $this->id,
                'name_en' => $this->name_en,
                'name_fr' => $this->name_fr,
                'tid' => $this->tid,
                'uuid' => $this->uuid,
                'parent_tid' => $this->parent_tid,
                'parent_uuid' => $this->parent_uuid,
                'parent' => new ExperienceResource($this->whenLoaded('parent')),
            ],
            'can' => [
                'update' => false,
                'delete' => false,
            ]
        ]

    }
}
