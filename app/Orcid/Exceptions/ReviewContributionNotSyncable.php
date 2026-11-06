<?php

declare(strict_types=1);

namespace App\Orcid\Exceptions;

use App\Models\ManuscriptReviewContributor;
use RuntimeException;

/**
 * The review contribution is missing data ORCID requires (e.g. the default
 * organization has no ROR identifier). It stays pending until the data is fixed.
 */
class ReviewContributionNotSyncable extends RuntimeException
{
    public function __construct(public readonly ManuscriptReviewContributor $contributor, string $reason)
    {
        parent::__construct("Review contribution {$contributor->id} cannot be synced with ORCID: {$reason}");
    }
}
