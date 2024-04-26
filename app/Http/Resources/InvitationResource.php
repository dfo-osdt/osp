<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource for Invitation model.
 * @extends JsonResource<\App\Models\Invitation>
 * @mixin \App\Models\Invitation
 */
class InvitationResource extends JsonResource
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
