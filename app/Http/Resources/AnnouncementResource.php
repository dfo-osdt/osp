<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Announcement
 */
class AnnouncementResource extends JsonResource
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
                'title_en' => $this->title_en,
                'title_fr' => $this->title_fr,
                'text_en' => $this->text_en,
                'text_fr' => $this->text_fr,
                'start_at' => $this->start_at,
                'end_at' => $this->end_at,
            ],
            'can' => [
                'update' => false,
                'delete' => false,
            ],
        ];
    }
}
