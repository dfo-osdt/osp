<?php

namespace App\Observers;

use App\Enums\ManagementReviewStepDecision;
use App\Enums\ManagementReviewStepStatus;
use App\Events\ManagementReviewStepCreated;
use App\Models\ManagementReviewDelegation;
use App\Models\ManagementReviewStep;

class ManagementReviewStepObserver
{
    public function created(ManagementReviewStep $managementReviewStep): void
    {
        if ($managementReviewStep->status !== ManagementReviewStepStatus::PENDING) {
            return;
        }

        $delegation = ManagementReviewDelegation::query()
            ->active()
            ->where('user_id', $managementReviewStep->user_id)
            ->latest()
            ->first();

        if (! $delegation) {
            return;
        }

        $delegateStep = new ManagementReviewStep;
        $delegateStep->user_id = $delegation->delegate_user_id;
        $delegateStep->status = ManagementReviewStepStatus::PENDING;
        $delegateStep->decision = ManagementReviewStepDecision::NONE;
        $delegateStep->manuscript_record_id = $managementReviewStep->manuscript_record_id;
        $delegateStep->previous_step_id = $managementReviewStep->id;
        $delegateStep->decision_expected_by = $managementReviewStep->decision_expected_by;
        $delegateStep->saveQuietly();

        event(new ManagementReviewStepCreated($delegateStep));

        $managementReviewStep->status = ManagementReviewStepStatus::REASSIGN;
        $managementReviewStep->comments = $delegation->getComment();
        $managementReviewStep->completed_at = now();
        $managementReviewStep->saveQuietly();
    }
}
