<?php

declare(strict_types=1);

namespace App\Orcid;

use Illuminate\Support\Facades\Cache;
use VincentAuger\OrcidSdk\Data\Record\Record;
use VincentAuger\OrcidSdk\Data\Works\Work;
use VincentAuger\OrcidSdk\Exceptions\OrcidAuthException;
use VincentAuger\OrcidSdk\Exceptions\OrcidException;
use VincentAuger\OrcidSdk\Orcid;

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
        return $this->withToken(fn (string $token): Record => $this->orcid->publicApi($token)->record($orcid)->wholeRecord());
    }

    /**
     * Works in full (the record only has their summaries, without contributors), by put-code, read by batches of
     * Orcid::BULK_LIMIT. A put-code ORCID no longer knows is left out.
     *
     * @param  list<int>  $putCodes
     * @return array<int, Work>
     *
     * @throws OrcidException
     */
    public function works(string $orcid, array $putCodes): array
    {
        $works = [];

        foreach (array_chunk($putCodes, Orcid::BULK_LIMIT) as $batch) {
            $works += $this->withToken(fn (string $token): array => $this->orcid->publicApi($token)->record($orcid)->works()->getMany($batch)->works);
        }

        return $works;
    }

    /**
     * Read with the read-public token, and get a new one once if ORCID rejects it (revoked or expired early)
     *
     * @template T
     *
     * @param  \Closure(string): T  $read
     * @return T
     *
     * @throws OrcidException
     */
    private function withToken(\Closure $read): mixed
    {
        // a refused token request is not retried, only a rejected token
        $token = $this->token();

        try {
            return $read($token);
        } catch (OrcidAuthException) {
            Cache::forget(self::TOKEN_CACHE_KEY);

            return $read($this->token());
        }
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
