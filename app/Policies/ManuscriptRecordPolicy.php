<?php

namespace App\Policies;

use App\Enums\ManuscriptRecordStatus;
use App\Enums\ManuscriptRecordType;
use App\Enums\Permissions\UserPermission;
use App\Models\ManuscriptRecord;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ManuscriptRecordPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user): bool
    {
        // this should stay false as the "draft" MRF should
        // never be seen by "anyone".
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

        if ($manuscriptRecord->manuscriptAuthors->contains('author.user_id', $user->id)) {
            return true;
        }

        // is this shared with the user?
        $manuscriptRecord->load('shareables');
        if ($manuscriptRecord->shareables->firstWhere('user_id', $user->id)?->isViewable()) {
            return true;
        }

        // Regional editor access - can view MRFs in their region
        $regionSlug = $manuscriptRecord->region->slug ?? null;
        if ($regionSlug && $user->can("can_view_{$regionSlug}_mrfs")) {
            // since regional editors will often help, we allow them to see draft manuscript records
            return true;
        }

        if ($user->can(UserPermission::VIEW_ANY_MANUSCRIPT_RECORD)) {
            return $manuscriptRecord->status !== ManuscriptRecordStatus::DRAFT;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can(UserPermission::CREATE_MANUSCRIPT_RECORDS);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ManuscriptRecord $manuscriptRecord)
    {
        switch ($manuscriptRecord->status) {
            case ManuscriptRecordStatus::DRAFT:
                if ($user->id === $manuscriptRecord->user_id) {
                    return true;
                }

                // Is the the user an author on this manuscript?
                if ($manuscriptRecord->manuscriptAuthors->contains('author.user_id', $user->id)) {
                    return true;
                }

                // Regional editor access
                $regionSlug = $manuscriptRecord->region->slug ?? null;
                if ($regionSlug && $user->can("can_edit_{$regionSlug}_mrfs")) {
                    return true;
                }

                return $manuscriptRecord->shareables->firstWhere('user_id', $user->id)?->isEditable();
            case ManuscriptRecordStatus::IN_REVIEW:
                // Existing reviewer access
                if ($manuscriptRecord->managementReviewSteps->contains('user_id', $user->id)) {
                    return true;
                }

                // Regional editor access
                $regionSlug = $manuscriptRecord->region->slug ?? null;
                return $regionSlug && $user->can("can_edit_{$regionSlug}_mrfs");
            default:
                return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ManuscriptRecord $manuscriptRecord)
    {
        // can delete if the manuscript is in draft state
        if ($manuscriptRecord->status === ManuscriptRecordStatus::DRAFT) {
            if ($user->id === $manuscriptRecord->user_id) {
                return true;
            }

            return $manuscriptRecord->shareables->firstWhere('user_id', $user->id)?->isDeletable();
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ManuscriptRecord $manuscriptRecord): bool
    {
        return false;
    }

    /**
     * Can the user attach a copy of the manuscript to the record?
     */
    public function attachManuscript(User $user, ManuscriptRecord $manuscriptRecord): ?bool
    {

        // cannot attach a manuscript if the manuscript has been accepted to a publication
        // user should update the publication instead
        if ($manuscriptRecord->status === ManuscriptRecordStatus::ACCEPTED) {
            return false;
        }

        // can attach if the manuscript is the owner of the manuscript
        if ($user->id === $manuscriptRecord->user_id) {
            return true;
        }

        if ($manuscriptRecord->manuscriptAuthors->contains('author.user_id', $user->id)) {
            return true;
        }

        if ($manuscriptRecord->shareables->firstWhere('user_id', $user->id)?->isEditable()) {
            return true;
        }
        return null;
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

        if ($manuscriptRecord->manuscriptAuthors->contains('author.user_id', $user->id)) {
            return true;
        }

        // can only submit if the user is the owner of the manuscript
        return $user->id === $manuscriptRecord->user_id;
    }

    /**
     * A user can withdraw a manuscript from review
     */
    public function withdraw(User $user, ManuscriptRecord $manuscriptRecord)
    {
        $allowedStatus = collect([
            ManuscriptRecordStatus::SUBMITTED,
            ManuscriptRecordStatus::REVIEWED,
        ]);

        if ($allowedStatus->contains($manuscriptRecord->status) === false) {
            return false;
        }

        // can only withdraw if the user is the owner of the manuscript
        return $user->id === $manuscriptRecord->user_id;
    }

    /**
     * A user can mark the manuscript as submitted
     */
    public function markSubmitted(User $user, ManuscriptRecord $manuscriptRecord): ?bool
    {
        // can only mark as submitted if the manuscript is reviewed
        if ($manuscriptRecord->status !== ManuscriptRecordStatus::REVIEWED) {
            return false;
        }

        // can only mark as submitted if the user is the owner of the manuscript
        if ($user->id === $manuscriptRecord->user_id) {
            return true;
        }

        if ($manuscriptRecord->manuscriptAuthors->contains('author.user_id', $user->id)) {
            return true;
        }
        if ($manuscriptRecord->shareables->firstWhere('user_id', $user->id)?->isEditable()) {
            return true;
        }
        return null;
    }

    /**
     * A user can mark the manuscript as accepted
     */
    public function markAccepted(User $user, ManuscriptRecord $manuscriptRecord): ?bool
    {
        // can only mark as accepted if the manuscript is reviewed or submitted
        if (! in_array($manuscriptRecord->status, [ManuscriptRecordStatus::REVIEWED, ManuscriptRecordStatus::SUBMITTED])) {
            return false;
        }

        // can only mark as accepted if the user is the owner of the manuscript
        if ($user->id === $manuscriptRecord->user_id) {
            return true;
        }
        if ($manuscriptRecord->manuscriptAuthors->contains('author.user_id', $user->id)) {
            return true;
        }
        if ($manuscriptRecord->shareables->firstWhere('user_id', $user->id)?->isEditable()) {
            return true;
        }
        return null;
    }

    public function submitToPreprint(User $user, ManuscriptRecord $manuscriptRecord)
    {

        // make sure it's a preprrint
        if ($manuscriptRecord->type !== ManuscriptRecordType::PREPRINT) {
            return false;
        }

        // can only mark as accepted if the manuscript is reviewed, submitted, or accepted (correction)
        if (! in_array($manuscriptRecord->status, [ManuscriptRecordStatus::REVIEWED, ManuscriptRecordStatus::SUBMITTED, ManuscriptRecordStatus::ACCEPTED])) {
            return false;
        }

        // A user can do this multiple times, for example, if they made a mistake in the URL or date.
        if ($user->id === $manuscriptRecord->user_id) {
            return true;
        }
        if ($manuscriptRecord->manuscriptAuthors->contains('author.user_id', $user->id)) {
            return true;
        }
        return (bool) $manuscriptRecord->shareables->firstWhere('user_id', $user->id)?->isEditable();
    }

    /**
     * Which users can share this manuscript?
     */
    public function share(User $user, ManuscriptRecord $manuscriptRecord)
    {
        if ($manuscriptRecord->manuscriptAuthors->contains('author.user_id', $user->id)) {
            return true;
        }

        return $user->id === $manuscriptRecord->user_id;
    }

    public function updateMedia(User $user, ManuscriptRecord $manuscriptRecord, Media $media): bool
    {
        return false;
    }

    public function deleteMedia(User $user, ManuscriptRecord $manuscriptRecord, Media $media)
    {
        if ($media->getCustomProperty('locked') === true) {
            return false;
        }

        return $this->update($user, $manuscriptRecord);
    }

    public function downloadMedia(User $user, ManuscriptRecord $manuscriptRecord, Media $media)
    {
        return $this->view($user, $manuscriptRecord);
    }
}
