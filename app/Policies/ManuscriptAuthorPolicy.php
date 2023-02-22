<?php

namespace App\Policies;

use App\Enums\ManuscriptRecordStatus;
use App\Models\ManuscriptAuthor;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ManuscriptAuthorPolicy
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
    public function view(User $user, ManuscriptAuthor $manuscriptAuthor)
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ManuscriptAuthor $manuscriptAuthor)
    {
        $manuscriptAuthor->load('manuscriptRecord');

        // can update if the manuscript is in draft and the user is the manuscript owner
        if ($manuscriptAuthor->manuscriptRecord->status !== ManuscriptRecordStatus::DRAFT) {
            return false;
        }

        return $manuscriptAuthor->manuscriptRecord->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ManuscriptAuthor $manuscriptAuthor)
    {
        $manuscriptAuthor->load('manuscriptRecord');

        // can update if the manuscript is in draft and the user is the manuscript owner
        if ($manuscriptAuthor->manuscriptRecord->status !== ManuscriptRecordStatus::DRAFT) {
            return false;
        }

        return $manuscriptAuthor->manuscriptRecord->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ManuscriptAuthor $manuscriptAuthor)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ManuscriptAuthor $manuscriptAuthor)
    {
        return false;
    }
}
