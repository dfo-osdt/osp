<?php

namespace App\Http\Integrations\Orcid\Requests;

use App\Http\Integrations\Orcid\Enums\ActivitiesScopeEndpoint;
use App\Http\Integrations\Orcid\Exceptions\OrcidIntegrationException;
use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetActivityRequest extends Request
{
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    public function __construct(
        protected string $putcode,
        protected ActivitiesScopeEndpoint $endpoint = ActivitiesScopeEndpoint::EMPLOYMENT
    ) {
        if (empty($putcode)) {
            throw new OrcidIntegrationException('Putcode is required for this request');
        }
    }

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return $this->endpoint->value.'/'.$this->putcode;
    }
}
