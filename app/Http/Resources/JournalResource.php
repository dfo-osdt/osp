<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
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
                'title' => $this->title,
                'publisher' => $this->publisher,
                'issn' => $this->issn,
            ],
            'can' => [
                'update' => false,
                'delete' => false,
            ],
        ];
    }
}
