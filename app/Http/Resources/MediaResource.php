<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource for Media model.
 * @mixin \Spatie\MediaLibrary\MediaCollections\Models\Media
 */
class MediaResource extends JsonResource
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
            'uuid' => $this->uuid,
            'file_name' => $this->file_name,
            'size_bytes' => $this->size,
            'created_at' => $this->created_at,
            'collection_name' => $this->collection_name,
            'mime_type' => $this->mime_type,
            'locked' => $this->getCustomProperty('locked', false),
        ];
    }
}
