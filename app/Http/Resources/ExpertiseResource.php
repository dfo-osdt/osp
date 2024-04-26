<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 *
 * @mixin \App\Models\Expertise
 */
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
            ],
            'can' => [
                'update' => false,
                'delete' => false,
            ],
        ];
    }
}
