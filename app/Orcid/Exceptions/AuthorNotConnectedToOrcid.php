<?php

declare(strict_types=1);

namespace App\Orcid\Exceptions;

use App\Models\Author;
use RuntimeException;

/**
 * The author has no usable ORCID connection: not verified, no token, expired
 * token, missing write scope or an invalid ORCID iD.
 */
class AuthorNotConnectedToOrcid extends RuntimeException
{
    public function __construct(public readonly Author $author, string $reason)
    {
        parent::__construct("Author {$author->id} is not connected to ORCID: {$reason}");
    }
}
