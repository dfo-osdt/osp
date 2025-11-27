<?php

namespace App\Policies;

use App\Models\FundingSource;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FundingSourcePolicy
{
    use HandlesAuthorization;

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
    public function view(User $user, FundingSource $fundingSource)
    {
        // user can view if they have permission to view the fundable
        return $user->can('view', $fundingSource->fundable);
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
    public function update(User $user, FundingSource $fundingSource)
    {
        // user can update if they have permission to update the fundable
        return $user->can('update', $fundingSource->fundable);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FundingSource $fundingSource)
    {
        // user can delete if they have permission to update the fundable
        return $user->can('update', $fundingSource->fundable);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FundingSource $fundingSource): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FundingSource $fundingSource): bool
    {
        return false;
    }
}
