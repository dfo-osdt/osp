<?php

namespace App\Http\Integrations\Orcid\Requests;

use App\Http\Integrations\Orcid\Data\EmploymentData;
use App\Http\Integrations\Orcid\Enums\ActivitiesScopeEndpoint;
use Saloon\Http\Response;

class GetEmploymentRequest extends GetActivityRequest
{
    public function __construct(string $putcode)
    {
        parent::__construct($putcode);
    }

    public function createDtoFromResponse(Response $response): EmploymentData
    {
        $data = $response->json();

        return EmploymentData::from($data);
    }
}
