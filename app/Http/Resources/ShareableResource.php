<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource for Shareable model.
 *
 * @extends JsonResource<\App\Models\Shareable>
 *
 * @mixin \App\Models\Shareable
 */
class ShareableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => [
                'id' => $this->id,
                'user_id' => $this->user_id,
                'shared_by' => $this->shared_by,
                'can_edit' => $this->can_edit,
                'can_delete' => $this->can_delete,
                'expires_at' => $this->expires_at,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'user' => $this->whenLoaded('user', fn () => new UserResource($this->user)),
                'sharingUser' => $this->whenLoaded('sharingUser', fn () => new UserResource($this->sharingUser)),
            ],
        ];
    }
}
