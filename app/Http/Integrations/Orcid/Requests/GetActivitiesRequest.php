<?php

namespace App\Http\Integrations\Orcid\Requests;

use App\Http\Integrations\Orcid\Enums\ActivitiesScopeListEndpoint;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Symfony\Component\CssSelector\Node\FunctionNode;

class GetActivitiesRequest extends Request
{
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    public function __construct(
        protected ActivitiesScopeListEndpoint $endpoint = ActivitiesScopeListEndpoint::EMPLOYMENTS
    ) {}


    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return $this->endpoint->value;
    }
}
