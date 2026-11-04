<?php

declare(strict_types=1);

namespace App\Orcid;

/**
 * Tells whether a DOI is one of a set of known DOIs, e.g. an author's OSP
 * publications, to avoid showing or pushing the same work twice. DOIs are
 * compared case-insensitively, whether written bare ("10.1139/abc"), as a
 * resolver URL ("https://doi.org/10.1139/abc") or as a "doi:" URI.
 */
final readonly class DoiMatcher
{
    /** @var array<string, true> */
    private array $known;

    /**
     * @param  iterable<string|null>  $dois
     */
    public function __construct(iterable $dois)
    {
        $known = [];

        foreach ($dois as $doi) {
            $normalized = self::normalize($doi);

            if ($normalized !== null) {
                $known[$normalized] = true;
            }
        }

        $this->known = $known;
    }

    /**
     * The bare, lowercase DOI ("10.1139/abc"), or null when the value is not a DOI
     */
    public static function normalize(?string $doi): ?string
    {
        $bare = preg_replace('~^(?:(?:https?://)?(?:dx\.|www\.)?doi\.org/|doi:)\s*~i', '', trim((string) $doi));
        $bare = mb_strtolower(trim((string) $bare));

        return str_starts_with($bare, '10.') ? $bare : null;
    }

    /**
     * Whether any of the DOIs is a known one
     *
     * @param  iterable<string|null>  $dois
     */
    public function matchesAny(iterable $dois): bool
    {
        foreach ($dois as $doi) {
            $normalized = self::normalize($doi);

            if ($normalized !== null && isset($this->known[$normalized])) {
                return true;
            }
        }

        return false;
    }
}
