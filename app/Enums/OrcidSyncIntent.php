<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Whether an author wants an item on their ORCID record. Null means they have not decided yet.
 *
 * Wanted: OSP adds the item to ORCID and keeps it up to date.
 * Declined: OSP never adds the item (e.g. the author removed it from ORCID).
 */
enum OrcidSyncIntent: string
{
    case WANTED = 'wanted';
    case DECLINED = 'declined';
}
