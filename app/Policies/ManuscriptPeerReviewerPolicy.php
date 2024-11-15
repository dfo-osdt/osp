<?php

namespace App\Policies;

use App\Models\ManuscriptPeerReviewer;
use App\Models\User;

class ManuscriptPeerReviewerPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ManuscriptPeerReviewer $manuscriptPeerReviewer): bool
    {
        return $user->can('view', $manuscriptPeerReviewer->manuscriptRecord);
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
    public function update(User $user, ManuscriptPeerReviewer $manuscriptPeerReviewer): bool
    {
        return $user->can('update', $manuscriptPeerReviewer->manuscriptRecord);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ManuscriptPeerReviewer $manuscriptPeerReviewer): bool
    {
        return $user->can('update', $manuscriptPeerReviewer->manuscriptRecord);
    }
}
