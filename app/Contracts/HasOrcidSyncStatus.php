<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Enums\OrcidSyncStatus;

/**
 * An item OSP syncs to the author's ORCID record (peer review, employment, later works)
 */
interface HasOrcidSyncStatus
{
    /**
     * Where the item stands with ORCID, for auditing.
     */
    public function orcidSyncStatus(): OrcidSyncStatus;
}
