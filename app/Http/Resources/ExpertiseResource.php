<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Expertise;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Expertise
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
                'is_validated' => $this->is_validated,
            ],
            'can' => [
                'update' => false,
                'delete' => false,
            ],
        ];
    }
}
