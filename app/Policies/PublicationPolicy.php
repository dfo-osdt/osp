<?php

namespace App\Policies;

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
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Publication  $publication
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
        if ($publication->publicationAuthors->contains('user_id', $user->id)) {
            return true;
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
        if ($publication->publicationAuthors->contains('user_id', $user->id)) {
            return true;
        }

        return ! $publication->isUnderEmbargo();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('create_publications');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Publication  $publication
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
     * @param  \App\Models\User  $user
     * @param  \App\Models\Publication  $publication
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
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Publication  $publication
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Publication $publication)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Publication  $publication
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Publication $publication)
    {
        //
    }
}
