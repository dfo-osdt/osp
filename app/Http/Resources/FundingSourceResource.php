<?php

namespace App\Http\Resources;

use Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class FundingSourceResource extends JsonResource
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
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'title' => $this->title,
                'description' => $this->description,
                'funder_id' => $this->funder_id,
                // relationships
                'funder' => new FunderResource($this->whenLoaded('funder')),
            ],
            'can' => [
                'update' => Auth::user()->can('update', $this->resource),
                'delete' => Auth::user()->can('delete', $this->resource),
            ],
        ];
    }
}
