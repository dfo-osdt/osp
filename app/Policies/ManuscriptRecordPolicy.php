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
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, ManuscriptRecord $manuscriptRecord)
    {
        // owners can view their own manuscripts
        if ($user->id === $manuscriptRecord->user_id) {
            return true;
        }

        // reviewers can view manuscripts they've reviewed or are reviewing
        if ($manuscriptRecord->managementReviewSteps->contains('user_id', $user->id)) {
            return true;
        }

        // is this a manuscript author?
        $manuscriptRecord->load('manuscriptAuthors.author');

        return $manuscriptRecord->manuscriptAuthors->contains('author.user_id', $user->id);
    }

    /**
     * Determine whether the user can create models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('create_manuscript_records');
    }

    /**
     * Determine whether the user can update the model.
     *
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
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ManuscriptRecord $manuscriptRecord)
    {
        return false;
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
