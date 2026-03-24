<?php

namespace App\Http\Resources;

use App\Models\PublicationAuthor;
use Auth;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
                'organization' => OrganizationResource::make($this->whenLoaded('organization')),
                'author' => AuthorResource::make($this->whenLoaded('author')),
            ],
            'can' => [
                'update' => Auth::user()->can('update', $this->resource),
                'delete' => Auth::user()->can('delete', $this->resource),
            ],

        ];
    }
}
