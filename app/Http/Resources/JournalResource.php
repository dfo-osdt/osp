<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource for Journal model.
 * @extends JsonResource<\App\Models\Journal>
 * @mixin \App\Models\Journal
 */
class JournalResource extends JsonResource
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
                'title_en' => $this->title_en,
                'title_fr' => $this->title_fr,
                'publisher' => $this->publisher,
            ],
            'can' => [
                'update' => false,
                'delete' => false,
            ],
        ];
    }
}
