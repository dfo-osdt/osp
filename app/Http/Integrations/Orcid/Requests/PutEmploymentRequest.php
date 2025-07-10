<?php

namespace App\Http\Integrations\Orcid\Requests;

use App\Http\Integrations\Orcid\Data\EmploymentData;
use App\Http\Integrations\Orcid\Enums\ActivitiesScopeEndpoint;
use App\Http\Integrations\Orcid\Exceptions\OrcidIntegrationException;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class PutEmploymentRequest extends Request implements HasBody
{
    use HasJsonBody;

    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::PUT;

    public function __construct(
        protected readonly EmploymentData $employmentData,
        protected ActivitiesScopeEndpoint $endpoint = ActivitiesScopeEndpoint::EMPLOYMENT
    ) {
        if (empty($this->employmentData->putCode)) {
            throw new OrcidIntegrationException('Putcode is required for this request');
        }
    }

    protected function defaultBody(): array
    {
        return $this->employmentData->toArray();
    }

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return $this->endpoint->value.'/'.$this->employmentData->putCode;
    }
}
