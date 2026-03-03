<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\NotificationGroupMember
 */
class NotificationGroupMemberResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'data' => [
                'id' => $this->id,
                'user_id' => $this->user_id,
                'member_user_id' => $this->member_user_id,
                'expires_at' => $this->expires_at?->toISOString(),
                'created_at' => $this->created_at->toISOString(),
                'updated_at' => $this->updated_at->toISOString(),
                'user' => UserResource::make($this->whenLoaded('user')),
                'member' => UserResource::make($this->whenLoaded('member')),
            ],
        ];
    }
}
