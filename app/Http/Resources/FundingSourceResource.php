<?php

namespace App\Http\Resources;

use Auth;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\FundingSource
 */
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
                'fundable_id' => $this->fundable_id,
                'fundable_type' => $this->fundableType($this->fundable_type),
                // relationships
                'funder' => new FunderResource($this->whenLoaded('funder')),
            ],
            'can' => [
                'update' => Auth::user()->can('update', $this->resource),
                'delete' => Auth::user()->can('delete', $this->resource),
            ],
        ];
    }

    private function fundableType(string $type): string
    {
        return match ($type) {
            \App\Models\ManuscriptRecord::class => 'manuscript-records',
            \App\Models\Publication::class => 'publications',
            default => throw new Exception('Unknown fundable type: '.$type),
        };
    }
}
