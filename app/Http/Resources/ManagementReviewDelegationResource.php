<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/**
 * @mixin \App\Models\ManagementReviewDelegation
 */
class ManagementReviewDelegationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'data' => [
                'id' => $this->id,
                'user_id' => $this->user_id,
                'delegate_user_id' => $this->delegate_user_id,
                'starts_at' => $this->starts_at?->toISOString(),
                'ends_at' => $this->ends_at->toISOString(),
                'ended_early_at' => $this->ended_early_at?->toISOString(),
                'comment' => $this->comment,
                'is_active' => $this->isActive(),
                'is_scheduled' => $this->isScheduled(),
                'created_at' => $this->created_at->toISOString(),
                'updated_at' => $this->updated_at->toISOString(),
                'user' => UserResource::make($this->whenLoaded('user')),
                'delegate' => UserResource::make($this->whenLoaded('delegate')),
            ],
            'can' => [
                'delete' => $this->isScheduled() && $this->user_id === Auth::id(),
            ],
        ];
    }
}
