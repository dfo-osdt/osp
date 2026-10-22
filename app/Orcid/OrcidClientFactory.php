<?php

declare(strict_types=1);

namespace App\Orcid;

use App\Models\Author;
use App\Orcid\Exceptions\AuthorNotConnectedToOrcid;
use VincentAuger\OrcidSdk\Enums\Environment;
use VincentAuger\OrcidSdk\Enums\Scope;
use VincentAuger\OrcidSdk\Orcid;
use VincentAuger\OrcidSdk\OrcidOAuth;
use VincentAuger\OrcidSdk\Resources\RecordResource;
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
     * The ORCID record of an author, through the Member API with the author's token
     *
     * @throws AuthorNotConnectedToOrcid
     */
    public function forAuthor(Author $author): RecordResource
    {
        if (! $author->hasValidOrcidCredentials()) {
            throw new AuthorNotConnectedToOrcid($author, 'no valid ORCID credentials with the /activities/update scope');
        }

        if (! OrcidId::isValid((string) $author->orcid)) {
            throw new AuthorNotConnectedToOrcid($author, 'invalid ORCID iD');
        }

        return Orcid::member((string) $author->orcid_access_token, $this->environment, $author->orcidScopes())
            ->record((string) $author->orcid);
    }
}
