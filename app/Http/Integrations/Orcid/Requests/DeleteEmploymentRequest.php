<?php

namespace App\Http\Integrations\Orcid\Requests;

use App\Http\Integrations\Orcid\Enums\ActivitiesScopeEndpoint;
use App\Http\Integrations\Orcid\Exceptions\OrcidIntegrationException;
use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteEmploymentRequest extends Request
{
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::DELETE;

    protected ActivitiesScopeEndpoint $endpoint = ActivitiesScopeEndpoint::EMPLOYMENT;

    public function __construct(readonly protected string $putCode)
    {

        if (empty($this->putCode)) {
            throw new OrcidIntegrationException('Putcode is required for this request');
        }
    }

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return $this->endpoint->value.'/'.$this->putCode;
    }
}
