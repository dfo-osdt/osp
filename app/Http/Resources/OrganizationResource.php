<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
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
            'id' => $this->id,
            'is_validated' => $this->is_validated,
            'name_en' => $this->name_en,
            'name_fr' => $this->name_fr,
            'abbr_en' => $this->abbr_en,
            'abbr_fr' => $this->abbr_fr,
        ];
    }
}
