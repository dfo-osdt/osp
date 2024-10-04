<?php

namespace App\Http\Integrations\Orcid\Requests;

use App\Http\Integrations\Orcid\Data\EmploymentData;
use App\Http\Integrations\Orcid\Enums\ActivitiesScopeEndpoint;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class PostEmploymentRequest extends Request implements HasBody
{
    use HasJsonBody;

    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::POST;

    public function __construct(readonly protected EmploymentData $employmentData) {}

    protected function defaultBody(): array
    {
        return $this->employmentData->toArray();
    }

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return ActivitiesScopeEndpoint::EMPLOYMENT->value;
    }
}
