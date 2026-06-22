<?php

namespace App\Actions;

use App\Enums\ManagementReviewStepDecision;
use App\Enums\ManagementReviewStepStatus;
use App\Events\ManagementReviewStepCreated;
use App\Models\ManagementReviewStep;
use App\Models\User;

class AdminReassignManagementReviewStep
{
    public static function handle(ManagementReviewStep $step, User $admin, int $nextUserId, ?string $comment): void
    {
        $attribution = "Action performed by Admin ({$admin->email})";
        $fullComment = filled($comment)
            ? "{$comment}\n\n{$attribution}"
            : $attribution;

        $newStep = new ManagementReviewStep;
        $newStep->user_id = $nextUserId;
        $newStep->status = ManagementReviewStepStatus::PENDING;
        $newStep->decision = ManagementReviewStepDecision::NONE;
        $newStep->manuscript_record_id = $step->manuscript_record_id;
        $newStep->previous_step_id = $step->id;
        $newStep->decision_expected_by = $step->decision_expected_by;
        $newStep->saveOrFail();

        event(new ManagementReviewStepCreated($newStep));

        $step->status = ManagementReviewStepStatus::REASSIGN;
        $step->comments = $fullComment;
        $step->completed_at = now();
        $step->saveQuietly();
    }
}
