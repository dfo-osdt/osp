<?php

namespace App\Policies;

use App\Models\Author;
use App\Models\AuthorEmployment;
use App\Models\User;

class AuthorEmploymentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, Author $author): bool
    {
        return $user->author->id === $author->id;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AuthorEmployment $authorEmployment): bool
    {
        return $user->author->id === $authorEmployment->author_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Author $author): bool
    {
        if ($user->author->id !== $author->id) {
            return false;
        }

        return $user->can('create_author_employments');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AuthorEmployment $authorEmployment): bool
    {
        return $user->author->id === $authorEmployment->author_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AuthorEmployment $authorEmployment): bool
    {
        return $user->author->id === $authorEmployment->author_id;
    }
}
