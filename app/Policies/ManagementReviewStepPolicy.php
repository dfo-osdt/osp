<?php

namespace App\Policies;

use App\Enums\ManagementReviewStepStatus;
use App\Models\ManagementReviewStep;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ManagementReviewStepPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ManagementReviewStep  $managementReviewStep
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, ManagementReviewStep $managementReviewStep)
    {
        // if a user can view the manuscript, they can view the management review step
        return $user->can('view', $managementReviewStep->manuscriptRecord);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ManagementReviewStep  $managementReviewStep
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ManagementReviewStep $managementReviewStep)
    {
        // is the status pending?
        if ($managementReviewStep->status !== ManagementReviewStepStatus::PENDING) {
            return false;
        }

        // is the user the owner of the manuscript step?
        return $user->id === $managementReviewStep->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ManagementReviewStep  $managementReviewStep
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ManagementReviewStep $managementReviewStep)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ManagementReviewStep  $managementReviewStep
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ManagementReviewStep $managementReviewStep)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ManagementReviewStep  $managementReviewStep
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ManagementReviewStep $managementReviewStep)
    {
        //
    }
}
