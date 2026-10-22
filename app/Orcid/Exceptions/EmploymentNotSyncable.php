<?php

declare(strict_types=1);

namespace App\Orcid\Exceptions;

use App\Models\AuthorEmployment;
use RuntimeException;

/**
 * The employment is missing data ORCID requires (e.g. the organization has no
 * country or ROR identifier). It stays pending until the data is fixed.
 */
class EmploymentNotSyncable extends RuntimeException
{
    public function __construct(public readonly AuthorEmployment $authorEmployment, string $reason)
    {
        parent::__construct("Employment {$authorEmployment->id} cannot be synced with ORCID: {$reason}");
    }
}
