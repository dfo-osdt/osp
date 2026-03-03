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

        // Delegation chains are intentionally not followed. The delegate step
        // is created with saveQuietly() so the observer does not fire again.
        // This ensures delegation from A to B is explicit — B cannot
        // implicitly re-delegate A's reviews to C via their own delegation.

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
