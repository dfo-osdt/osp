<?php

namespace App\Http\Integrations\Orcid\Requests;

use App\Http\Integrations\Orcid\Enums\PersonalInfoEndpoints;
use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetPersonalInfo extends Request
{
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;


    public function __construct(
        protected PersonalInfoEndpoints $endpoint
    ) {
    }

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return $this->endpoint->value;
    }
}
