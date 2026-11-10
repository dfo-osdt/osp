<?php

declare(strict_types=1);

namespace App\Orcid;

use App\Models\Author;
use App\Models\Organization;
use App\Rules\Orcid;
use Illuminate\Support\Facades\Cache;
use VincentAuger\OrcidSdk\Data\Affiliations\Affiliation;
use VincentAuger\OrcidSdk\Data\Organization\DisambiguatedOrganization;
use VincentAuger\OrcidSdk\Data\Person\Email;
use VincentAuger\OrcidSdk\Data\Record\Record;
use VincentAuger\OrcidSdk\Data\Search\ExpandedSearchResult;
use VincentAuger\OrcidSdk\Enums\AffiliationType;
use VincentAuger\OrcidSdk\Exceptions\OrcidException;
use VincentAuger\OrcidSdk\Support\OrcidId;

/**
 * Finds people on ORCID to add as authors, and what their public record fills
 * in on the new author form. ORCID's answers are cached (searches for ten
 * minutes, records for a day); whether someone is already an author, and which
 * OSP organization an employment is with, are worked out on every read so
 * they follow OSP.
 *
 * @phpstan-type FoundRecord array{orcid: string, name: string|null, given_names: string|null, family_name: string|null, other_names: list<string>, emails: list<string>, institutions: list<string>}
 * @phpstan-type Found array{orcid: string, name: string|null, given_names: string|null, family_name: string|null, other_names: list<string>, emails: list<string>, institutions: list<string>, existing_author_id: int|null}
 * @phpstan-type Search array{num_found: int, results: list<Found>}
 * @phpstan-type Employment array{name: string|null, ror: string|null, start_date: string|null}
 * @phpstan-type CachedPreview array{first_name: string|null, last_name: string|null, credit_name: string|null, emails: list<string>, employments: list<Employment>}
 * @phpstan-type PreviewAffiliation array{name: string|null, organization_id: int|null, start_date: string|null, suggested: bool}
 * @phpstan-type Preview array{orcid: string, first_name: string|null, last_name: string|null, credit_name: string|null, emails: list<string>, affiliations: list<PreviewAffiliation>, existing_author_id: int|null}
 */
class OrcidAuthorLookup
{
    public const int RESULTS = 10;

    private const int SEARCH_SECONDS = 600;

    private const int PREVIEW_SECONDS = 86400;

    /**
     * Characters with a meaning in ORCID's (Solr) search syntax, searched for literally
     */
    private const string SOLR_SPECIAL_CHARACTERS = '/([+\-!(){}\[\]^"~*?:\\\\\/&|])/';

    public function __construct(
        private readonly PublicRecordReader $reader,
        private readonly OrcidClientFactory $orcid,
    ) {}

    /**
     * The first records matching an ORCID iD (bare or as a link) or a name
     *
     * @return Search
     *
     * @throws OrcidException
     */
    public function search(string $query): array
    {
        /** @var array{num_found: int, results: list<FoundRecord>} $found */
        $found = Cache::remember(
            'orcid-author-search:'.md5($this->searchKey($query)),
            self::SEARCH_SECONDS,
            fn (): array => $this->searchOrcid($query),
        );

        $existing = Author::query()->whereIn('orcid', array_column($found['results'], 'orcid'))->pluck('id', 'orcid');

        return [
            'num_found' => $found['num_found'],
            'results' => array_map(fn (array $result): array => [...$result, 'existing_author_id' => $existing[$result['orcid']] ?? null], $found['results']),
        ];
    }

    /**
     * What the public record fills in on the new author form. Only current employments are offered as the
     * affiliation, newest first; the suggested one is the first OSP knows by its ROR, else the newest.
     *
     * @return Preview
     *
     * @throws OrcidException
     */
    public function preview(string $orcid): array
    {
        $orcid = OrcidId::normalize($orcid);

        /** @var CachedPreview $record */
        $record = Cache::remember(
            "orcid-author-preview:{$orcid}",
            self::PREVIEW_SECONDS,
            fn (): array => $this->toPreview($this->reader->wholeRecord($orcid)),
        );

        $affiliations = $this->affiliations($record['employments']);
        $url = $this->orcid->recordUrl($orcid);

        return [
            'orcid' => $url,
            'first_name' => $record['first_name'],
            'last_name' => $record['last_name'],
            'credit_name' => $record['credit_name'],
            'emails' => $record['emails'],
            'affiliations' => $affiliations,
            'existing_author_id' => $this->existingAuthorId($url, $record['emails']),
        ];
    }

    /**
     * The same search typed with other capitals or spaces is the same search
     */
    private function searchKey(string $query): string
    {
        return mb_strtolower((string) preg_replace('/\s+/', ' ', trim($query)));
    }

    /**
     * @return array{num_found: int, results: list<FoundRecord>}
     *
     * @throws OrcidException
     */
    private function searchOrcid(string $query): array
    {
        $found = $this->reader->search($this->solrQuery($query), self::RESULTS);

        return [
            'num_found' => $found->numFound,
            'results' => array_map(fn (ExpandedSearchResult $result): array => [
                'orcid' => $this->orcid->recordUrl($result->orcid),
                'name' => $result->displayName(),
                'given_names' => $result->givenNames,
                'family_name' => $result->familyName,
                'other_names' => $result->otherNames,
                'emails' => $result->emails,
                'institutions' => $result->institutionNames,
            ], $found->results),
        ];
    }

    /**
     * An ORCID iD is looked up directly; anything else is a name, each word of
     * which (in any order) must be in the given and family names, the credit
     * name or another name of the record
     */
    private function solrQuery(string $query): string
    {
        $query = trim($query);

        if (OrcidId::isValid($query) && (! str_contains($query, '/') || preg_match(Orcid::URL_PREFIX, $query))) {
            return 'orcid:'.OrcidId::normalize($query);
        }

        $words = preg_split('/[\s,]+/', $query, flags: PREG_SPLIT_NO_EMPTY) ?: [];
        $terms = '('.implode(' AND ', array_map(fn (string $word): string => (string) preg_replace(self::SOLR_SPECIAL_CHARACTERS, '\\\\$1', $word), $words)).')';

        return "given-and-family-names:{$terms} OR credit-name:{$terms} OR other-names:{$terms}";
    }

    /**
     * @return CachedPreview
     */
    private function toPreview(Record $record): array
    {
        $emails = $record->person->emails;
        usort($emails, fn (Email $a, Email $b): int => $b->primary <=> $a->primary);

        $employments = array_map(fn (Affiliation $affiliation): array => [
            'name' => $affiliation->organization?->name,
            'ror' => $affiliation->organization?->disambiguatedOrganization?->source === DisambiguatedOrganization::ROR
                ? $affiliation->organization->disambiguatedOrganization->identifier
                : null,
            'start_date' => $affiliation->startDate?->__toString(),
        ], array_filter(
            $record->activities->affiliations(AffiliationType::Employment),
            fn (Affiliation $affiliation): bool => $affiliation->endDate === null,
        ));

        // "2019" < "2019-05" < "2020": the dates compare as strings
        usort($employments, fn (array $a, array $b): int => $b['start_date'] <=> $a['start_date']);

        return [
            'first_name' => $record->person->name?->givenNames,
            'last_name' => $record->person->name?->familyName,
            'credit_name' => $record->person->name?->creditName,
            'emails' => array_values(array_unique(array_map(fn (Email $email): string => mb_strtolower($email->email), $emails))),
            'employments' => $employments,
        ];
    }

    /**
     * @param  list<Employment>  $employments
     * @return list<PreviewAffiliation>
     */
    private function affiliations(array $employments): array
    {
        $organizations = $this->organizationsByRor(array_values(array_filter(array_column($employments, 'ror'))));

        $affiliations = array_map(fn (array $employment): array => [
            'name' => $employment['name'],
            'organization_id' => $employment['ror'] === null ? null : ($organizations[self::rorKey($employment['ror'])] ?? null),
            'start_date' => $employment['start_date'],
            'suggested' => false,
        ], $employments);

        $known = array_filter($affiliations, fn (array $affiliation): bool => $affiliation['organization_id'] !== null);
        $suggested = array_key_first($known) ?? array_key_first($affiliations);

        if ($suggested !== null) {
            $affiliations[$suggested]['suggested'] = true;
        }

        return $affiliations;
    }

    /**
     * The OSP organization of each ROR, the validated one first when OSP has several
     *
     * @param  list<string>  $rors
     * @return array<string, int>
     */
    private function organizationsByRor(array $rors): array
    {
        if ($rors === []) {
            return [];
        }

        $identifiers = array_merge(...array_map(fn (string $ror): array => [self::rorKey($ror), self::rorKey($ror).'/'], $rors));

        return Organization::query()
            ->whereIn('ror_identifier', $identifiers)
            ->orderByDesc('is_validated')
            ->orderBy('id')
            ->get(['id', 'ror_identifier'])
            ->reduce(fn (array $byRor, Organization $organization): array => $byRor + [self::rorKey((string) $organization->ror_identifier) => $organization->id], []);
    }

    /**
     * ROR identifiers compare without case or a trailing slash
     */
    private static function rorKey(string $ror): string
    {
        return mb_strtolower(rtrim($ror, '/'));
    }

    /**
     * @param  list<string>  $emails
     */
    private function existingAuthorId(string $orcidUrl, array $emails): ?int
    {
        $byOrcid = Author::query()->where('orcid', $orcidUrl)->value('id');

        if ($byOrcid !== null || $emails === []) {
            return $byOrcid;
        }

        return Author::query()->whereIn('email', $emails)->value('id');
    }
}
