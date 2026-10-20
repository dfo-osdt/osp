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
        $firstFilled = collect(['sector_id', 'region_id'])
            ->filter(fn (string $attribute): bool => $author->wasChanged($attribute)
                && $author->getOriginal($attribute) === null
                && $author->{$attribute} !== null);

        if ($firstFilled->isEmpty()) {
            return;
        }

        $defaultOrganizationId = Organization::getDefaultOrganization()->id;

        $firstFilled->each(function (string $attribute) use ($author, $defaultOrganizationId): void {
            foreach ([$author->manuscriptAuthors(), $author->publicationAuthors()] as $authorships) {
                $authorships
                    ->where('organization_id', $defaultOrganizationId)
                    ->whereNull($attribute)
                    ->update([$attribute => $author->{$attribute}]);
            }
        });

        activity()
            ->performedOn($author)
            ->withProperties($firstFilled->mapWithKeys(fn (string $attribute): array => [$attribute => $author->{$attribute}])->all())
            ->log('Author profile first filled and backfilled to existing manuscripts and publications');
    }
}
