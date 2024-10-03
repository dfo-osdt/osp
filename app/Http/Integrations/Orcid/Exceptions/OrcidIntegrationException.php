<?php

namespace App\Http\Integrations\Orcid\Exceptions;

use Exception;

class OrcidIntegrationException extends Exception
{
    /**
     * Create a new OrcidIntegrationException instance.
     */
    public function __construct(string $message = 'An error occurred in the ORCID integration', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
