<?php

namespace App\Http\Resources;

use App\Models\Invitation;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Invitation
 */
class InvitationResource extends JsonResource
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
                'user_id' => $this->user_id,
                'invited_by' => $this->invited_by,
                'invited_at' => $this->created_at,
                'registered_at' => $this->registered_at,
                'user' => UserResource::make($this->user),
                'invited_by_user' => UserResource::make($this->whenLoaded('invitedByUser')),

            ],
        ];
    }
}
