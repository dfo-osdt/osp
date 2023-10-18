<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpertiseResource extends JsonResource
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
                'parent_tid' => $this->parent_tid,
                'taxonomy_path_en' => $this->whenLoaded('ancestors', function () {
                    return $this->ancestors->pluck('name_en')->reverse()->implode(' > ');
                }),
                'taxonomy_path_fr' => $this->whenLoaded('ancestors', function () {
                    return $this->ancestors->pluck('name_fr')->reverse()->implode(' > ');
                }),
                'ancestors' => ExpertiseResource::collection($this->whenLoaded('ancestors')),
                'children' => ExpertiseResource::collection($this->whenLoaded('children')),
            ],
            'can' => [
                'update' => false,
                'delete' => false,
            ],
        ];
    }
}
