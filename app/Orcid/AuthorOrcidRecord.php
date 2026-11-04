<?php

declare(strict_types=1);

namespace App\Orcid;

use App\Models\Author;
use App\Models\Publication;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use VincentAuger\OrcidSdk\Data\Affiliations\Affiliation;
use VincentAuger\OrcidSdk\Data\Common\ExternalId;
use VincentAuger\OrcidSdk\Data\Organization\DisambiguatedOrganization;
use VincentAuger\OrcidSdk\Data\Record\Record;
use VincentAuger\OrcidSdk\Data\Works\WorkGroup;
use VincentAuger\OrcidSdk\Enums\AffiliationType;
use VincentAuger\OrcidSdk\Enums\ExternalIdRelationship;
use VincentAuger\OrcidSdk\Enums\WorkType;
use VincentAuger\OrcidSdk\Exceptions\OrcidException;

/**
 * What OSP shows of an author's public ORCID record on their author profile,
 * cached as plain arrays so ORCID is read at most about once an hour per
 * author: fresh for an hour, then served stale for up to a week while it is
 * refreshed in the background after the response. A failed refresh keeps the
 * stale record and is not tried again for an hour.
 *
 * Each entry remembers the ORCID iD it was read for, so a refresh that lands
 * after the author reconnected with another iD is never shown.
 *
 * The works already listed in the author's OSP publications are left out when
 * the record is read, not when it is cached, so they follow the publications.
 *
 * @phpstan-type ShownAffiliation array{organization: string|null, ror: string|null, department: string|null, role: string|null, start_date: string|null, end_date: string|null}
 * @phpstan-type ShownWork array{title: string|null, type: string, date: string|null, journal: string|null, doi: string|null, url: string|null}
 * @phpstan-type CachedWork array{work: ShownWork, dois: list<string>}
 * @phpstan-type Cached array{biography: string|null, employments: list<ShownAffiliation>, educations: list<ShownAffiliation>, peer_review_count: int, works: list<CachedWork>}
 * @phpstan-type Shown array{biography: string|null, employments: list<ShownAffiliation>, educations: list<ShownAffiliation>, peer_review_count: int, works: list<ShownWork>, works_already_listed: int}
 */
class AuthorOrcidRecord
{
    private const int FRESH_SECONDS = 3600;

    private const int STALE_SECONDS = 7 * 86400;

    public function __construct(private readonly PublicRecordReader $reader) {}

    /**
     * @return Shown
     *
     * @throws OrcidException when ORCID cannot be read and nothing is cached
     */
    public function read(Author $author, User $viewer): array
    {
        $record = $this->cached($author);
        $listed = $this->listedDois($author, $viewer);
        $works = array_values(array_filter($record['works'], fn (array $work): bool => ! $listed->matchesAny($work['dois'])));

        return [
            ...$record,
            'works' => array_map(fn (array $work): array => $work['work'], $works),
            'works_already_listed' => count($record['works']) - count($works),
        ];
    }

    /**
     * The DOIs of the publications the viewer sees in the author's profile
     */
    private function listedDois(Author $author, User $viewer): DoiMatcher
    {
        return new DoiMatcher(Publication::query()
            ->forUser($viewer)
            ->whereHas('publicationAuthors', fn (Builder $query) => $query->where('author_id', $author->id))
            ->whereNotNull('doi')
            ->pluck('doi'));
    }

    /**
     * @return Cached
     *
     * @throws OrcidException
     */
    private function cached(Author $author): array
    {
        /** @var array{orcid: string, record: Cached, read_at: int}|null $cached */
        $cached = Cache::get(self::cacheKey($author));

        if ($cached === null || $cached['orcid'] !== $author->orcid) {
            return $this->fetch($author);
        }

        if (now()->getTimestamp() >= $cached['read_at'] + self::FRESH_SECONDS
            && Cache::add(self::refreshKey($author), true, self::FRESH_SECONDS)) {
            defer(fn () => $this->refresh($author), self::refreshKey($author));
        }

        return $cached['record'];
    }

    /**
     * Drop the cached record, e.g. when the author disconnects from ORCID
     */
    public static function forget(Author $author): void
    {
        Cache::forget(self::cacheKey($author));
        Cache::forget(self::refreshKey($author));
    }

    /**
     * @return Cached
     *
     * @throws OrcidException
     */
    private function fetch(Author $author): array
    {
        $orcid = (string) $author->orcid;
        $record = $this->toArray($this->reader->wholeRecord($orcid));

        Cache::put(self::cacheKey($author), ['orcid' => $orcid, 'record' => $record, 'read_at' => now()->getTimestamp()], self::STALE_SECONDS);
        Cache::forget(self::refreshKey($author));

        return $record;
    }

    private function refresh(Author $author): void
    {
        try {
            $this->fetch($author);
        } catch (OrcidException $exception) {
            Log::warning('ORCID record could not be refreshed, the cached one is kept', ['author_id' => $author->id, 'error' => $exception->getMessage()]);
        }
    }

    /**
     * @return Cached
     */
    private function toArray(Record $record): array
    {
        return [
            'biography' => $record->person->biography?->content,
            'employments' => $this->affiliations($record, AffiliationType::Employment),
            'educations' => $this->affiliations($record, AffiliationType::Education),
            'peer_review_count' => count($record->activities->peerReviews->groups),
            'works' => array_map($this->work(...), $record->activities->works->groups),
        ];
    }

    /**
     * A group of works as the version ORCID displays, with the work's own DOIs
     * from every version to tell whether it is already listed in OSP (not e.g.
     * the DOI of the book a chapter is part of)
     *
     * @return CachedWork
     */
    private function work(WorkGroup $group): array
    {
        $preferred = $group->preferred();
        $dois = array_values(array_unique(array_filter(array_map(
            fn (ExternalId $id): ?string => $id->type === 'doi' && $id->relationship === ExternalIdRelationship::Self
                ? DoiMatcher::normalize($id->normalizedValue ?? $id->value)
                : null,
            [...$preferred->externalIds, ...$group->externalIds],
        ))));

        return [
            'work' => [
                'title' => $preferred->title?->__toString(),
                // a type the SDK does not know is shown as "other"
                'type' => $preferred->type->value ?? WorkType::Other->value,
                'date' => $preferred->publicationDate?->__toString(),
                'journal' => $preferred->journalTitle,
                'doi' => $dois[0] ?? null,
                'url' => $preferred->url?->value,
            ],
            'dois' => $dois,
        ];
    }

    /**
     * Every public affiliation of the type, whoever added it (OSP included):
     * current ones first, then the newest first
     *
     * @return list<ShownAffiliation>
     */
    private function affiliations(Record $record, AffiliationType $type): array
    {
        $affiliations = array_map(fn (Affiliation $affiliation): array => [
            'organization' => $affiliation->organization?->name,
            'ror' => $this->ror($affiliation),
            'department' => $affiliation->departmentName,
            'role' => $affiliation->roleTitle,
            'start_date' => $affiliation->startDate?->__toString(),
            'end_date' => $affiliation->endDate?->__toString(),
        ], $record->activities->affiliations($type));

        // "2019" < "2019-05" < "2020": the dates compare as strings
        usort($affiliations, fn (array $a, array $b): int => [$b['end_date'] === null, $b['end_date'], $b['start_date']]
            <=> [$a['end_date'] === null, $a['end_date'], $a['start_date']]);

        return $affiliations;
    }

    /**
     * The organization's ROR, when ORCID identifies it by one (not by e.g. Ringgold or GRID)
     */
    private function ror(Affiliation $affiliation): ?string
    {
        $organization = $affiliation->organization?->disambiguatedOrganization;

        return $organization?->source === DisambiguatedOrganization::ROR ? $organization->identifier : null;
    }

    private static function cacheKey(Author $author): string
    {
        return "orcid-record:{$author->id}";
    }

    /**
     * Set while a refresh was tried in the last hour
     */
    private static function refreshKey(Author $author): string
    {
        return "orcid-record-refresh:{$author->id}";
    }
}
