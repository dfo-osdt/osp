<?php

declare(strict_types=1);

namespace App\Orcid;

use App\Models\Author;
use App\Orcid\Exceptions\AuthorNotConnectedToOrcid;
use VincentAuger\OrcidSdk\Enums\Environment;
use VincentAuger\OrcidSdk\Enums\Scope;
use VincentAuger\OrcidSdk\Orcid;
use VincentAuger\OrcidSdk\OrcidOAuth;
use VincentAuger\OrcidSdk\Support\OrcidId;

/**
 * Builds ORCID SDK clients from the OSP configuration (config/osp.php).
 * This is the only place that reads the ORCID configuration.
 */
class OrcidClientFactory
{
    public function __construct(
        public readonly ?string $clientId,
        private readonly ?string $clientSecret,
        public readonly ?string $redirectUri,
        public readonly Environment $environment,
    ) {}

    public static function fromConfig(): self
    {
        return new self(
            clientId: config('osp.orcid.client_id'),
            clientSecret: config('osp.orcid.client_secret'),
            redirectUri: config('osp.orcid.redirect_uri'),
            environment: config('osp.orcid.use_sandbox') ? Environment::Sandbox : Environment::Production,
        );
    }

    /**
     * Whether the OAuth client is configured (client id and redirect URI)
     */
    public function isConfigured(): bool
    {
        return filled($this->clientId) && filled($this->redirectUri);
    }

    /**
     * Client for the OAuth endpoints (authorize URL, token exchange, revoke)
     */
    public function oauth(): OrcidOAuth
    {
        return new OrcidOAuth((string) $this->clientId, (string) $this->clientSecret, $this->environment);
    }

    /**
     * The scopes OSP asks authors for
     *
     * @return Scope[]
     */
    public function scopes(): array
    {
        return Scope::completeAccess();
    }

    /**
     * The link to an ORCID record, on orcid.org or the sandbox
     */
    public function recordUrl(string $orcid): string
    {
        return $this->environment->oauthUrl().'/'.OrcidId::normalize($orcid);
    }

    /**
     * Member API client for reading any record with OSP's /read-public client credentials token: only what
     * researchers made public, with the Member API's higher limits
     */
    public function memberReadApi(string $readPublicToken): Orcid
    {
        return Orcid::member($readPublicToken, environment: $this->environment);
    }

    /**
     * Member API client bound to the author's ORCID record, using the author's token and scopes
     *
     * @throws AuthorNotConnectedToOrcid
     */
    public function forAuthor(Author $author): Orcid
    {
        if (! $author->hasValidOrcidCredentials()) {
            throw new AuthorNotConnectedToOrcid($author, 'no valid ORCID credentials with the /activities/update scope');
        }

        if (! OrcidId::isValid((string) $author->orcid)) {
            throw new AuthorNotConnectedToOrcid($author, 'invalid ORCID iD');
        }

        return Orcid::member((string) $author->orcid_access_token, (string) $author->orcid, $author->orcidScopes(), $this->environment);
    }
}
