<?php

namespace App\Http\Resources;

use Auth;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource for Organization model.
 * @extends JsonResource<\App\Models\Organization>
 * @mixin \App\Models\Organization
 */
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
            'data' => [
                'id' => $this->id,
                'is_validated' => $this->is_validated,
                'name_en' => $this->name_en,
                'name_fr' => $this->name_fr,
                'abbr_en' => $this->abbr_en,
                'abbr_fr' => $this->abbr_fr,
                'country_code' => $this->country_code,
                'ror_identifier' => $this->ror_identifier,
            ],
            'can' => [
                'update' => Auth::user()->can('update', $this->resource),
                'delete' => Auth::user()->can('delete', $this->resource),
            ],
        ];
    }
}
