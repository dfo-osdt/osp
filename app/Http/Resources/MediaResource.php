<?php

namespace App\Http\Resources;

use App\Enums\SensitivityLabel;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @mixin Media
 */
class MediaResource extends JsonResource
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
                'uuid' => $this->uuid,
                'file_name' => $this->file_name,
                'size_bytes' => $this->size,
                'created_at' => $this->created_at,
                'collection_name' => $this->collection_name,
                'mime_type' => $this->mime_type,
                'locked' => $this->getCustomProperty('locked', false),
                'sensitivity_label' => $this->getCustomProperty('sensitivity_label', SensitivityLabel::Unclassified),
                'supplementary_file_type' => $this->when($this->hasCustomProperty('supplementary_file_type'), $this->getCustomProperty('supplementary_file_type')),
                'description' => $this->when($this->hasCustomProperty('description'), $this->getCustomProperty('description')),
            ],
            'can' => [
                'update' => Gate::allows('update', $this->resource),
                'delete' => Gate::allows('delete', $this->resource),
                'download' => Gate::allows('download', $this->resource),
            ],
        ];
    }
}
