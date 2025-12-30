<?php

namespace App\Http\Integrations\Orcid\Requests;

use App\Http\Integrations\Orcid\Data\EmploymentData;
use Saloon\Http\Response;

class GetEmploymentRequest extends GetActivityRequest
{
    public function createDtoFromResponse(Response $response): EmploymentData
    {
        $data = $response->json();

        return EmploymentData::from($data);
    }
}
