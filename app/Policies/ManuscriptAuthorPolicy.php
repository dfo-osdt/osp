<?php

namespace App\Policies;

use App\Models\ManuscriptAuthor;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class ManuscriptAuthorPolicy
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
    public function view(User $user, ManuscriptAuthor $manuscriptAuthor)
    {
        return Gate::allows('view', $manuscriptAuthor->manuscriptRecord);
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
    public function update(User $user, ManuscriptAuthor $manuscriptAuthor)
    {
        return Gate::allows('update', $manuscriptAuthor->manuscriptRecord);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ManuscriptAuthor $manuscriptAuthor)
    {
        return $this->update($user, $manuscriptAuthor);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ManuscriptAuthor $manuscriptAuthor): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ManuscriptAuthor $manuscriptAuthor): bool
    {
        return false;
    }
}
