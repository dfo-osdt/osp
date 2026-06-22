<?php

namespace App\Actions\Delegations;

use App\Enums\ManagementReviewStepDecision;
use App\Enums\ManagementReviewStepStatus;
use App\Events\ManagementReviewStepCreated;
use App\Models\ManagementReviewDelegation;
use App\Models\ManagementReviewStep;

class ReassignPendingStepsOnDelegationStart
{
    public static function handle(ManagementReviewDelegation $delegation): void
    {
        $steps = ManagementReviewStep::pending()
            ->where('user_id', $delegation->user_id)
            ->get();

        foreach ($steps as $step) {
            $delegateStep = new ManagementReviewStep;
            $delegateStep->user_id = $delegation->delegate_user_id;
            $delegateStep->status = ManagementReviewStepStatus::PENDING;
            $delegateStep->decision = ManagementReviewStepDecision::NONE;
            $delegateStep->manuscript_record_id = $step->manuscript_record_id;
            $delegateStep->previous_step_id = $step->id;
            $delegateStep->decision_expected_by = $step->decision_expected_by;
            $delegateStep->saveQuietly();

            event(new ManagementReviewStepCreated($delegateStep));

            $step->status = ManagementReviewStepStatus::REASSIGN;
            $step->comments = $delegation->getComment();
            $step->completed_at = now();
            $step->saveQuietly();
        }
    }
}
