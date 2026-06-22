<?php

namespace App\Http\Resources;

use App\Enums\ManagementReviewStepStatus;
use App\Models\ManuscriptRecord;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Date;

/**
 * @mixin ManuscriptRecord
 */
class ManuscriptRecordSummaryResource extends JsonResource
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
                'type' => $this->type,
                'status' => $this->status,
                'title' => $this->title,
                'region_id' => $this->region_id,
                'user_id' => $this->user_id,

                // dates and times
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'sent_for_review_at' => $this->sent_for_review_at,
                'business_days_in_review' => $this->sent_for_review_at
                    ? (int) Date::parse($this->sent_for_review_at)->diffInBusinessDays(now())
                    : null,
                'reviewed_at' => $this->reviewed_at,
                'submitted_to_journal_on' => $this->submitted_to_journal_on,
                'accepted_on' => $this->accepted_on,
                'withdrawn_on' => $this->withdrawn_on,
                // relationships - if loaded
                'region' => RegionResource::make($this->whenLoaded('region')),
                'manuscript_authors' => ManuscriptAuthorResource::collection($this->whenLoaded('manuscriptAuthors')),
                'user' => UserResource::make($this->whenLoaded('user')),
                'active_management_review_step' => $this->when(
                    $this->relationLoaded('managementReviewSteps'),
                    function (): ?array {
                        $active = $this->managementReviewSteps
                            ->first(fn ($s): bool => in_array($s->status, [
                                ManagementReviewStepStatus::PENDING,
                                ManagementReviewStepStatus::ON_HOLD,
                            ]));

                        if ($active === null) {
                            return null;
                        }

                        return [
                            'user_id' => $active->user_id,
                            'user_name' => $active->relationLoaded('user') ? $active->user->full_name : null,
                            'status' => $active->status,
                            'decision_expected_by' => $active->decision_expected_by,
                            'is_overdue' => $active->decision_expected_by !== null && $active->decision_expected_by->isPast(),
                        ];
                    }
                ),
            ],
            'can' => [
                'delete' => $request->user()->can('delete', $this->resource),
            ],
        ];
    }
}
