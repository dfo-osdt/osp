<?php

namespace App\Policies;

use App\Enums\ManuscriptRecordStatus;
use App\Models\ManuscriptRecord;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ManuscriptRecordPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ManuscriptRecord  $manuscriptRecord
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, ManuscriptRecord $manuscriptRecord)
    {
        // owners can view their own manuscripts
        if ($user->id === $manuscriptRecord->user_id) {
            return true;
        }

        // reviewers can view manuscripts they've reviewed
        $reviewerIds = $manuscriptRecord->managementReviewSteps->pluck('user_id')->toArray();
        if (in_array($user->id, $reviewerIds)) {
            return true;
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ManuscriptRecord  $manuscriptRecord
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ManuscriptRecord $manuscriptRecord)
    {
        // is the manuscript record in draft state?
        if ($manuscriptRecord->status !== ManuscriptRecordStatus::DRAFT) {
            return false;
        }

        // is the user the owner of the manuscript?
        return $user->id === $manuscriptRecord->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ManuscriptRecord  $manuscriptRecord
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ManuscriptRecord $manuscriptRecord)
    {
        // can delete if the manuscript is in draft state
        return $manuscriptRecord->status === ManuscriptRecordStatus::DRAFT;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ManuscriptRecord  $manuscriptRecord
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ManuscriptRecord $manuscriptRecord)
    {
        //
    }

    /**
     * Can the user attach an copy of the manuscript to the record?
     */
    public function attachManuscript(User $user, ManuscriptRecord $manuscriptRecord)
    {
        // can attach if the manuscript is in draft state
        return $user->id === $manuscriptRecord->user_id;
    }

    /**
     * A user can submit this manuscript for review
     */
    public function submitForReview(User $user, ManuscriptRecord $manuscriptRecord)
    {
        // can only submit if the manuscript is in draft state
        if ($manuscriptRecord->status !== ManuscriptRecordStatus::DRAFT) {
            return false;
        }

        // can only submit if the user is the owner of the manuscript
        return $user->id === $manuscriptRecord->user_id;
    }
}
