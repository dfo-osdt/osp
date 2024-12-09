<?php

namespace App\Http\Integrations\Orcid;

use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;

class OrcidMemberAPIConnector extends Connector
{
    /**
     * OrcidMemberAPIConnector constructor.
     *
     * @param  string  $bearerToken  The Bearer Token to use for the API. This token is the one returned by the
     *                               ORICD API when the user authorizes the application to access their data
     *                               and can only be used to access their profile.
     * @param  string  $orcid  The ORCID iD of the user to access
     */
    public function __construct(
        protected readonly string $bearerToken,
        protected readonly string $orcid
    ) {}

    /**
     * The Base URL of the Member API this will use the sandbox if
     * the configuration is set to use the sandbox
     */
    public function resolveBaseUrl(): string
    {

        // are we using the sandbox?
        if (config('osp.orcid.use_sandbox')) {
            return 'https://api.sandbox.orcid.org/v3.0/'.$this->orcid;
        }

        return 'https://api.orcid.org/v3.0/'.$this->orcid;
    }

    /**
     * Default headers for every request
     */
    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/vnd.orcid+json',
            'Accept' => 'application/vnd.orcid+json',
        ];
    }

    /** Default Authentication for every request */
    protected function defaultAuth(): TokenAuthenticator
    {
        return new TokenAuthenticator($this->bearerToken);
    }

    /**
     * Default HTTP client options
     */
    protected function defaultConfig(): array
    {
        return [];
    }
}
