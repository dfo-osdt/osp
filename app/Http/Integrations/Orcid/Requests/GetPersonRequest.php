<?php

namespace App\Http\Integrations\Orcid\Requests;

use App\Http\Integrations\Orcid\Enums\PersonScopeEndpoint;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * ORCID Peroanl Info Request
 * https://github.com/ORCID/ORCID-Source/blob/development/orcid-api-web/tutorial/personal_info.md
 */
class GetPersonRequest extends Request
{
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    /**
     * GetPersonalInfo constructor.
     *
     * @param  PersonScopeEndpoint  $endpoint  The endpoint to request. By default this is the person endpoint
     *                                         which returns the full personal information of the user.
     * @param  string  $putcode  The putcode of the resource to request. This is optional and will return a single
     *                           resource if provided.
     */
    public function __construct(
        protected PersonScopeEndpoint $endpoint = PersonScopeEndpoint::PERSON,
        protected string $putcode = '',
    ) {}

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        if ($this->putcode !== '' && $this->putcode !== '0') {
            return $this->endpoint->value.'/'.$this->putcode;
        }

        return $this->endpoint->value;
    }
}
