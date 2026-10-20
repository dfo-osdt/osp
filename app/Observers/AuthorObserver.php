<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Author;
use App\Models\Organization;

class AuthorObserver
{
    /**
     * Sector, region and emeritus only apply to DFO authors, so they
     * are cleared when an author moves to another organization.
     */
    public function updating(Author $author): void
    {
        if (! $author->isDirty('organization_id') || $author->isInternal()) {
            return;
        }

        $author->sector_id = null;
        $author->region_id = null;
        $author->is_emeritus = false;
    }

    /**
     * When a DFO author fills their sector or region for the first
     * time, backfill the authorship rows created before it existed.
     * Rows that already hold a value are history and never change.
     */
    public function updated(Author $author): void
    {
        $defaultOrganizationId = Organization::getDefaultOrganization()->id;
        $backfilled = [];

        foreach (['sector_id', 'region_id'] as $attribute) {
            $isFirstFill = $author->wasChanged($attribute)
                && $author->getOriginal($attribute) === null
                && $author->{$attribute} !== null;

            if (! $isFirstFill) {
                continue;
            }

            foreach ([$author->manuscriptAuthors(), $author->publicationAuthors()] as $authorships) {
                $authorships
                    ->where('organization_id', $defaultOrganizationId)
                    ->whereNull($attribute)
                    ->update([$attribute => $author->{$attribute}]);
            }

            $backfilled[$attribute] = $author->{$attribute};
        }

        if ($backfilled === []) {
            return;
        }

        activity()
            ->performedOn($author)
            ->withProperties($backfilled)
            ->log('Author profile first filled and backfilled to existing manuscripts and publications');
    }
}
