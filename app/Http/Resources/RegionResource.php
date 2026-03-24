<?php

namespace App\Http\Resources;

use App\Models\Region;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Region
 */
class RegionResource extends JsonResource
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
                'name_en' => $this->name_en,
                'name_fr' => $this->name_fr,
                'slug' => $this->slug,
            ],
            'can' => [
                'update' => false,
                'delete' => false,
            ],
        ];
    }
}
