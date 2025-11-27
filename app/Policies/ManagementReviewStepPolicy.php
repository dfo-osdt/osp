<?php

namespace App\Policies;

use App\Enums\ManagementReviewStepStatus;
use App\Enums\ManuscriptRecordType;
use App\Enums\Permissions\UserPermission;
use App\Models\ManagementReviewStep;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ManagementReviewStepPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ManagementReviewStep $managementReviewStep)
    {
        // if a user can view the manuscript, they can view the management review step
        return $user->can('view', $managementReviewStep->manuscriptRecord);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ManagementReviewStep $managementReviewStep)
    {
        // is the status pending or on hold?
        $allowedStatuses = collect([
            ManagementReviewStepStatus::PENDING,
            ManagementReviewStepStatus::ON_HOLD,
        ]);

        if ($allowedStatuses->contains($managementReviewStep->status) === false) {
            return false;
        }

        // is the user the owner of the manuscript step?
        return $user->id === $managementReviewStep->user_id;
    }

    public function decide(User $user, ManagementReviewStep $managementReviewStep)
    {
        // is the status pending?
        if ($managementReviewStep->status !== ManagementReviewStepStatus::PENDING) {
            return false;
        }

        // is the user the owner of the manuscript step?
        return $user->id === $managementReviewStep->user_id;
    }

    public function complete(User $user, ManagementReviewStep $managementReviewStep)
    {
        if ($this->decide($user, $managementReviewStep) === false) {
            return false;
        }

        switch ($managementReviewStep->manuscriptRecord->type) {
            case ManuscriptRecordType::SECONDARY:
                return $user->can(UserPermission::COMPLETE_INTERNTAL_MANAGEMENT_REVIEW);
            default:
                return true;
        }
    }

    public function withdraw(User $user, ManagementReviewStep $managementReviewStep)
    {
        // is the status on hold?
        if ($managementReviewStep->status !== ManagementReviewStepStatus::ON_HOLD) {
            return false;
        }

        // is the user the owner of the manuscript step?
        return $user->id === $managementReviewStep->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ManagementReviewStep $managementReviewStep): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ManagementReviewStep $managementReviewStep): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ManagementReviewStep $managementReviewStep): bool
    {
        return false;
    }
}
