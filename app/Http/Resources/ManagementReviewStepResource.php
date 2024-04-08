<?php

namespace App\Http\Resources;

use Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class ManagementReviewStepResource extends JsonResource
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
                'manuscript_record_id' => $this->manuscript_record_id,
                'previous_step_id' => $this->previous_step_id,
                'user_id' => $this->user_id,
                'comments' => $this->comments ?? '',
                'status' => $this->status,
                'decision' => $this->decision,
                'decision_expected_by' => $this->decision_expected_by,
                'completed_at' => $this->completed_at,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                // relationships
                'manuscript_record' => ManuscriptRecordSummaryResource::make($this->whenLoaded('manuscriptRecord')),
                'previous_step' => ManagementReviewStepResource::make($this->whenLoaded('previousStep')),
                'user' => UserResource::make($this->whenLoaded('user')),
            ],
            'can' => [
                'update' => Auth::user()->can('update', $this->resource),
                'delete' => Auth::user()->can('delete', $this->resource),
            ],
        ];
    }
}
