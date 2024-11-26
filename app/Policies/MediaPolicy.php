<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * This policy class will be used to route to the correct policy
 * of the model to which it is associated.
 */
class MediaPolicy
{
    public function update(User $user, Media $media)
    {
        return Gate::allows('updateMedia', [$media->model, $media]);
    }

    public function delete(User $user, Media $media)
    {
        return Gate::allows('deleteMedia', [$media->model, $media]);
    }

    public function download(User $user, Media $media)
    {
        return Gate::allows('downloadMedia', [$media->model, $media]);
    }
}
