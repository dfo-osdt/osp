<?php

namespace App\Http\Resources;

use App\Models\Organization;
use Auth;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Organization
 */
class OrganizationResource extends JsonResource
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
