<?php

namespace App\Http\Integrations\Orcid\Requests;

use App\Http\Integrations\Orcid\Enums\PersonalInfoEndpoints;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * ORCID Peroanl Info Request
 * https://github.com/ORCID/ORCID-Source/blob/development/orcid-api-web/tutorial/personal_info.md
 *
 * @package App\Http\Integrations\Orcid\Requests
 */
class GetPersonalInfoRequest extends Request
{
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    /**
     * GetPersonalInfo constructor.
     *
     * @param PersonalInfoEndpoints $endpoint   The endpoint to request. By default this is the person endpoint
     *                                          which returns the full personal information of the user.
     */
    public function __construct(
        protected PersonalInfoEndpoints $endpoint = PersonalInfoEndpoints::PERSON
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
