<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\ManuscriptAuthor;
use App\Models\Organization;
use App\Models\PublicationAuthor;

/**
 * Keeps the DFO sector, region and emeritus snapshot of a manuscript
 * or publication author in line with the row's organization. DFO rows
 * copy the author's profile; other rows never hold these values.
 */
class AuthorshipSnapshotObserver
{
    /** @var list<string> */
    private const array SNAPSHOT_ATTRIBUTES = ['sector_id', 'region_id', 'is_emeritus'];

    /**
     * Copy the author's profile unless the caller already provided the
     * snapshot, as when a publication inherits it from its manuscript.
     */
    public function creating(ManuscriptAuthor|PublicationAuthor $authorship): void
    {
        if (! $this->isInternal($authorship)) {
            $this->clearSnapshot($authorship);

            return;
        }

        $provided = array_intersect_key($authorship->getAttributes(), array_flip(self::SNAPSHOT_ATTRIBUTES));

        if ($provided === []) {
            $this->copySnapshotFromAuthor($authorship);
        }
    }

    public function updating(ManuscriptAuthor|PublicationAuthor $authorship): void
    {
        if (! $authorship->isDirty('organization_id')) {
            return;
        }

        if ($this->isInternal($authorship)) {
            $this->copySnapshotFromAuthor($authorship);
        } else {
            $this->clearSnapshot($authorship);
        }
    }

    private function isInternal(ManuscriptAuthor|PublicationAuthor $authorship): bool
    {
        return $authorship->organization_id === Organization::getDefaultOrganization()->id;
    }

    private function copySnapshotFromAuthor(ManuscriptAuthor|PublicationAuthor $authorship): void
    {
        $author = $authorship->author;

        $authorship->sector_id = $author?->sector_id;
        $authorship->region_id = $author?->region_id;
        $authorship->is_emeritus = $author?->is_emeritus ?? false;
    }

    private function clearSnapshot(ManuscriptAuthor|PublicationAuthor $authorship): void
    {
        $authorship->sector_id = null;
        $authorship->region_id = null;
        $authorship->is_emeritus = null;
    }
}
