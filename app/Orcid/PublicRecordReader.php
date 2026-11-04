<?php

declare(strict_types=1);

namespace App\Orcid;

use Illuminate\Support\Facades\Cache;
use VincentAuger\OrcidSdk\Data\Record\Record;
use VincentAuger\OrcidSdk\Exceptions\OrcidAuthException;
use VincentAuger\OrcidSdk\Exceptions\OrcidException;

/**
 * Reads ORCID records through the public API with OSP's /read-public client
 * credentials token, so only what researchers made public is returned (never
 * limited-visibility items, whatever the author granted OSP).
 *
 * The token is requested on first use and reused until it expires.
 */
class PublicRecordReader
{
    private const string TOKEN_CACHE_KEY = 'orcid:read-public-token';

    public function __construct(private readonly OrcidClientFactory $orcid) {}

    /**
     * The public part of the whole record, in one request
     *
     * @throws OrcidException
     */
    public function wholeRecord(string $orcid): Record
    {
        $token = $this->token();

        try {
            return $this->read($token, $orcid);
        } catch (OrcidAuthException) {
            // the token was revoked or expired early: get a new one, once
            Cache::forget(self::TOKEN_CACHE_KEY);

            return $this->read($this->token(), $orcid);
        }
    }

    /**
     * @throws OrcidException
     */
    private function read(string $token, string $orcid): Record
    {
        return $this->orcid->publicApi($token)->record($orcid)->wholeRecord();
    }

    /**
     * @throws OrcidException
     */
    private function token(): string
    {
        $cached = Cache::get(self::TOKEN_CACHE_KEY);

        if (is_string($cached)) {
            return $cached;
        }

        $token = $this->orcid->oauth()->clientCredentials();
        Cache::put(self::TOKEN_CACHE_KEY, $token->accessToken, $token->expiresIn);

        return $token->accessToken;
    }
}
