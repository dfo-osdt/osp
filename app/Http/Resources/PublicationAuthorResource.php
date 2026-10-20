<?php

namespace App\Http\Resources;

use App\Models\PublicationAuthor;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/**
 * @mixin PublicationAuthor
 */
class PublicationAuthorResource extends JsonResource
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
                'publication_id' => $this->publication_id,
                'author_id' => $this->author_id,
                'organization_id' => $this->organization_id,
                'is_corresponding_author' => $this->is_corresponding_author,
                'is_group_author' => $this->is_group_author,
                'is_independent' => $this->isIndependent(),
                'sector_id' => $this->sector_id,
                'region_id' => $this->region_id,
                'is_emeritus' => $this->is_emeritus,
                'organization' => OrganizationResource::make($this->whenLoaded('organization')),
                'sector' => SectorResource::make($this->whenLoaded('sector')),
                'region' => RegionResource::make($this->whenLoaded('region')),
                'author' => AuthorResource::make($this->whenLoaded('author')),
            ],
            'can' => [
                'update' => Auth::user()->can('update', $this->resource),
                'delete' => Auth::user()->can('delete', $this->resource),
            ],

        ];
    }
}
