<?php

namespace App\Http\Resources;

use App\Models\Journal;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Journal
 */
class JournalResource extends JsonResource
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
