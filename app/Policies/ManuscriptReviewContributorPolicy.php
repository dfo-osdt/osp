<?php

namespace App\Policies;

use App\Models\ManuscriptReviewContributor;
use App\Models\User;

class ManuscriptReviewContributorPolicy
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
    public function view(User $user, ManuscriptReviewContributor $manuscriptReviewContributor): bool
    {
        return $user->can('view', $manuscriptReviewContributor->manuscriptRecord);
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
    public function update(User $user, ManuscriptReviewContributor $manuscriptReviewContributor): bool
    {
        return $user->can('update', $manuscriptReviewContributor->manuscriptRecord);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ManuscriptReviewContributor $manuscriptReviewContributor): bool
    {
        return $user->can('update', $manuscriptReviewContributor->manuscriptRecord);
    }
}
