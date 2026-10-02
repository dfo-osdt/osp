<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Organization;

class OrganizationObserver
{
    /**
     * Refuse any organization that would be a homonym of the
     * independent researcher sentinel once the sentinel exists.
     */
    public function creating(Organization $organization): void
    {
        if (! Organization::isIndependentName($organization->name_en)) {
            return;
        }

        $sentinelExists = Organization::query()
            ->where('name_en', config('osp.independent_organization'))
            ->whereNull('ror_identifier')
            ->exists();

        if ($sentinelExists) {
            throw new \InvalidArgumentException('An organization named "'.$organization->name_en.'" cannot be created: the name is reserved for independent researchers.');
        }
    }
}
