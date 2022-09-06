<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ManuscriptRecordResource extends JsonResource
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
            'data' => parent::toArray($request),
            'can' => [
                'update' => auth()->user()->can('update', $this->resource),
                'delete' => auth()->user()->can('delete', $this->resource),
            ],
        ];
    }
}
