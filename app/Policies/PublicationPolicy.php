<?php

namespace App\Policies;

use App\Enums\Permissions\UserPermission;
use App\Enums\PublicationStatus;
use App\Models\Publication;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PublicationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Publication $publication)
    {
        // if the status is published, then anyone can view it
        if ($publication->status == PublicationStatus::PUBLISHED) {
            return true;
        }

        // if they are the owner, then they can view it
        if ($publication->user_id == $user->id) {
            return true;
        }

        // if the user is an author on the publication, then they can view it
        $users = $publication->publicationAuthors()->with('author')->get()->pluck('author.user_id');
        if ($users->contains($user->id)) {
            return true;
        }

        // if the publication has a manuscript record, then the users that can view it, can view this publication
        if ($publication->manuscript_record_id) {
            return $user->can('view', $publication->manuscriptRecord);
        }

        return false;
    }

    /**
     * Determine whether the user can view the pdf of the publication
     */
    public function viewPdf(User $user, Publication $publication)
    {
        if ($publication->user_id == $user->id) {
            return true;
        }

        // if the user is an author on the publication, then they can view it
        $users = $publication->publicationAuthors()->with('author')->get()->pluck('author.user_id');
        if ($users->contains($user->id)) {
            return true;
        }

        // if the publication has a manuscript record, then the users that can view it, can view this publication
        if ($publication->manuscript_record_id) {
            return $user->can('view', $publication->manuscriptRecord);
        }

        return ! $publication->isUnderEmbargo();
    }

    /**
     * Determine whether the user can create models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can(UserPermission::CREATE_PUBLICATIONS);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Publication $publication)
    {
        // is the user the owner of the publication
        if ($user->id === $publication->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Publication $publication)
    {
        // if the publication is linked to a manuscript record
        // it can't be deleted.
        if ($publication->manuscript_record_id) {
            return false;
        }

        // is the user the owner of the publication
        if ($user->id === $publication->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Publication $publication)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Publication $publication)
    {
        return false;
    }
}
