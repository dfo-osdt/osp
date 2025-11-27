<?php

namespace App\Policies;

use App\Enums\Permissions\UserPermission;
use App\Models\PublicationAuthor;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PublicationAuthorPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, PublicationAuthor $publicationAuthor): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, PublicationAuthor $publicationAuthor)
    {

        if ($user->hasPermissionTo(UserPermission::UPDATE_PUBLICATIONS)) {
            return true;
        }

        $publicationAuthor->load('publication');

        return $user->id === $publicationAuthor->publication->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, PublicationAuthor $publicationAuthor)
    {
        if ($user->hasPermissionTo(UserPermission::UPDATE_PUBLICATIONS)) {
            return true;
        }

        $publicationAuthor->load('publication');

        return $user->id === $publicationAuthor->publication->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, PublicationAuthor $publicationAuthor): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, PublicationAuthor $publicationAuthor): bool
    {
        return false;
    }
}
