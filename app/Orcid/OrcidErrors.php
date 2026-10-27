<?php

declare(strict_types=1);

namespace App\Orcid;

use VincentAuger\OrcidSdk\Exceptions\OrcidAuthException;
use VincentAuger\OrcidSdk\Exceptions\OrcidException;
use VincentAuger\OrcidSdk\Exceptions\OrcidForbiddenException;

class OrcidErrors
{
    /**
     * Whether the author's token was revoked, expired or lacks a scope OSP needs:
     * the author must reconnect.
     */
    public static function revokesAccess(OrcidException $exception): bool
    {
        return $exception instanceof OrcidAuthException
            || ($exception instanceof OrcidForbiddenException && $exception->isMissingScope());
    }
}
