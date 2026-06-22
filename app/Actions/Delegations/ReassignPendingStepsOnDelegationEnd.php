<?php

namespace App\Actions\Delegations;

use App\Enums\ManagementReviewStepDecision;
use App\Enums\ManagementReviewStepStatus;
use App\Events\ManagementReviewStepCreated;
use App\Models\ManagementReviewDelegation;
use App\Models\ManagementReviewStep;
use Illuminate\Contracts\Database\Query\Builder;

class ReassignPendingStepsOnDelegationEnd
{
    public static function handle(ManagementReviewDelegation $delegation): void
    {
        $steps = ManagementReviewStep::pending()
            ->where('user_id', $delegation->delegate_user_id)
            ->whereHas('previousStep', fn (Builder $q) => $q->where('user_id', $delegation->user_id))
            ->get();

        if ($steps->isEmpty()) {
            return;
        }

        $nextDelegation = ManagementReviewDelegation::active()
            ->where('user_id', $delegation->user_id)
            ->latest()
            ->first();

        $nextUserId = $nextDelegation->delegate_user_id ?? $delegation->user_id;
        $comment = __('delegation.delegation_ended_comment');

        foreach ($steps as $step) {
            $newStep = new ManagementReviewStep;
            $newStep->user_id = $nextUserId;
            $newStep->status = ManagementReviewStepStatus::PENDING;
            $newStep->decision = ManagementReviewStepDecision::NONE;
            $newStep->manuscript_record_id = $step->manuscript_record_id;
            $newStep->previous_step_id = $step->id;
            $newStep->decision_expected_by = $step->decision_expected_by;
            $newStep->saveQuietly();

            event(new ManagementReviewStepCreated($newStep));

            $step->status = ManagementReviewStepStatus::REASSIGN;
            $step->comments = $comment;
            $step->completed_at = now();
            $step->saveQuietly();
        }
    }
}
