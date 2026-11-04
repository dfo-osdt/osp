<?php

declare(strict_types=1);

namespace App\Orcid;

use App\Models\Author;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use VincentAuger\OrcidSdk\Data\Record\Record;
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
 */
class AuthorOrcidRecord
{
    private const int FRESH_SECONDS = 3600;

    private const int STALE_SECONDS = 7 * 86400;

    public function __construct(private readonly PublicRecordReader $reader) {}

    /**
     * @return array{biography: string|null}
     *
     * @throws OrcidException when ORCID cannot be read and nothing is cached
     */
    public function read(Author $author): array
    {
        /** @var array{orcid: string, record: array{biography: string|null}, read_at: int}|null $cached */
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
     * @return array{biography: string|null}
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
     * @return array{biography: string|null}
     */
    private function toArray(Record $record): array
    {
        return [
            'biography' => $record->person->biography?->content,
        ];
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
