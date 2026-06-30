<?php

namespace App\Http\Resources;

use App\Enums\SensitivityLabel;
use App\Models\ManagementReviewStep;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

/**
 * @mixin ManagementReviewStep
 */
class ManagementReviewStepResource extends JsonResource
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
                'sensitivity_label' => SensitivityLabel::ProtectedA,
                // relationships
                'manuscript_record' => ManuscriptRecordSummaryResource::make($this->whenLoaded('manuscriptRecord')),
                'previous_step' => ManagementReviewStepResource::make($this->whenLoaded('previousStep')),
                'user' => UserResource::make($this->whenLoaded('user')),
                'can_complete' => Gate::allows('complete', $this->resource),
                'can_flag_to_binder' => Gate::allows('complete', $this->resource) && $this->relationLoaded('manuscriptRecord') && $this->manuscriptRecord->potential_public_interest,
                'can_forward' => Gate::allows('forward', $this->resource),
            ],
            'can' => [
                'update' => Gate::allows('update', $this->resource),
                'delete' => Gate::allows('delete', $this->resource),
            ],
        ];
    }
}
