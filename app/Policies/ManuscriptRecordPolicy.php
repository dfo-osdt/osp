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
        return $user->can('create_manuscript_records');
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
        // cannot attach a manuscript if the manuscript has been withheld so that
        // so that we keep version of the manuscript that was "withheld".
        if ($manuscriptRecord->status === ManuscriptRecordStatus::WITHHELD) {
            return false;
        }

        // cannot attach a manuscript if the manuscript has been accepted to a publication
        // user should update the publication instead
        if ($manuscriptRecord->status === ManuscriptRecordStatus::ACCEPTED) {
            return false;
        }

        // can attach if the manuscript is the owner of the manuscript
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

    /**
     * A user can withdraw a manuscript from review
     */
    public function withdraw(User $user, ManuscriptRecord $manuscriptRecord)
    {
        // Can only be withdrawn if the manuscript is reviewed or submitted
        if (! in_array($manuscriptRecord->status, [ManuscriptRecordStatus::SUBMITTED, ManuscriptRecordStatus::REVIEWED])) {
            return false;
        }

        // can only withdraw if the user is the owner of the manuscript
        return $user->id === $manuscriptRecord->user_id;
    }

    /**
     * A user can mark the manuscript as submitted
     */
    public function markSubmitted(User $user, ManuscriptRecord $manuscriptRecord)
    {
        // can only mark as submitted if the manuscript is reviewed
        if ($manuscriptRecord->status !== ManuscriptRecordStatus::REVIEWED) {
            return false;
        }

        // can only mark as submitted if the user is the owner of the manuscript
        return $user->id === $manuscriptRecord->user_id;
    }

    /**
     * A user can mark the manuscript as accepted
     */
    public function markAccepted(User $user, ManuscriptRecord $manuscriptRecord)
    {
        // can only mark as accepted if the manuscript is reviewed or submitted
        if (! in_array($manuscriptRecord->status, [ManuscriptRecordStatus::REVIEWED, ManuscriptRecordStatus::SUBMITTED])) {
            return false;
        }
        // can only mark as accepted if the user is the owner of the manuscript
        return $user->id === $manuscriptRecord->user_id;
    }
}
