<?php

namespace App\Policies;

use App\Enums\MediaCollection;
use App\Enums\Permissions\UserPermission;
use App\Enums\PublicationStatus;
use App\Enums\SupplementaryFileType;
use App\Models\Journal;
use App\Models\Publication;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PublicationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Publication $publication)
    {
        // if the status is published, then anyone can view it
        if ($publication->status == PublicationStatus::PUBLISHED) {
            return true;
        }

        if ($user->hasPermissionTo(UserPermission::UPDATE_PUBLICATIONS)) {
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
            $publication->load('manuscriptRecord.manuscriptAuthors.author');

            return $user->can('view', $publication->manuscriptRecord);
        }

        return false;
    }

    /**
     * Determine whether the user can download the given media.
     */
    public function downloadMedia(User $user, Publication $publication, Media $media): bool
    {

        if ($user->hasPermissionTo(UserPermission::UPDATE_PUBLICATIONS)) {
            return true;
        }

        if ($publication->user_id == $user->id) {
            return true;
        }

        if ($media->collection_name == MediaCollection::SUPPLEMENTARY_FILE->value && $publication->status == PublicationStatus::PUBLISHED) {
            $allowedSupplementaryFiles = [SupplementaryFileType::PREPRINT->value];
            if ($media->getCustomProperty('supplementary_file_type') && in_array($media->getCustomProperty('supplementary_file_type'), $allowedSupplementaryFiles)) {
                return true;
            }
        }

        // if the user is an author on the publication, then they can download it
        $users = $publication->publicationAuthors()->with('author')->get()->pluck('author.user_id');
        if ($users->contains($user->id)) {
            return true;
        }

        // if the publication has a manuscript record, then the users that can view it, can download
        if ($publication->manuscript_record_id) {
            return $user->can('view', $publication->manuscriptRecord);
        }

        // if it's a publication file and not under embargo, then it can be downloaded
        if ($media->collection_name === MediaCollection::PUBLICATION->value) {
            return ! $publication->isUnderEmbargo();
        }

        return false;
    }

    public function updateMedia(User $user, Publication $publication, Media $media): bool
    {
        return false;
    }

    public function deleteMedia(User $user, Publication $publication, Media $media)
    {
        return $this->update($user, $publication);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->can(UserPermission::CREATE_PUBLICATIONS);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Publication $publication)
    {
        if ($user->hasPermissionTo(UserPermission::UPDATE_PUBLICATIONS)) {
            return true;
        }

        // Regional editor access - can edit publication in their region
        $regionSlug = $publication->region->slug ?? null;
        if ($regionSlug && $user->can("can_edit_{$regionSlug}_pubs")) {
            return true;
        }

        // is the user the owner of the publication
        return $user->id === $publication->user_id;
    }

    /** Can the user publish this publication */
    public function publish(User $user, Publication $publication): bool
    {

        $isDfoSeries = $publication->journal->publisher == Journal::$dfoPublisher;

        if ($user->hasPermissionTo(UserPermission::PUBLISH_INTERNAL_REPORTS)) {
            return true;
        }

        // is the user the owner of the publication
        if ($user->id === $publication->user_id) {
            return ! $isDfoSeries;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Publication $publication): bool
    {
        // Cannot delete publications linked to manuscript records
        if ($publication->manuscript_record_id) {
            return false;
        }

        // Only users with DELETE_PUBLICATIONS permission can delete
        return $user->hasPermissionTo(UserPermission::DELETE_PUBLICATIONS);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Publication $publication): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Publication $publication): bool
    {
        return false;
    }
}
