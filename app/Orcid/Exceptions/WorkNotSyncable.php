<?php

declare(strict_types=1);

namespace App\Orcid\Exceptions;

use App\Models\PublicationAuthor;
use RuntimeException;

/**
 * The publication is not eligible for ORCID or has data ORCID would reject
 * (e.g. a title longer than ORCID allows). It stays pending until that changes.
 */
class WorkNotSyncable extends RuntimeException
{
    public function __construct(public readonly PublicationAuthor $publicationAuthor, string $reason)
    {
        parent::__construct("Publication author {$publicationAuthor->id} cannot be synced with ORCID as a work: {$reason}");
    }
}
