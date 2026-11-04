<?php

namespace App\Http\Controllers\Orcid;

use App\Models\Author;
use App\Orcid\AuthorOrcidRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use VincentAuger\OrcidSdk\Exceptions\OrcidException;
use VincentAuger\OrcidSdk\Support\OrcidId;

/**
 * The public part of an author's ORCID record, shown on their author profile.
 * The profile loads it after rendering, so it never waits on ORCID.
 *
 * The status is "empty" when the author is not connected to ORCID or chose not
 * to show their record, and "unavailable" when ORCID cannot be read and nothing
 * is cached.
 */
class AuthorOrcidRecordController
{
    public function __invoke(Author $author, AuthorOrcidRecord $records): JsonResponse
    {
        if (! $author->show_orcid_record || ! $author->hasValidOrcidCredentials() || ! OrcidId::isValid((string) $author->orcid)) {
            return $this->respond('empty');
        }

        try {
            return $this->respond('available', $records->read($author));
        } catch (OrcidException $exception) {
            Log::warning('ORCID record could not be read for the author profile', ['author_id' => $author->id, 'error' => $exception->getMessage()]);

            return $this->respond('unavailable');
        }
    }

    /**
     * @param  'empty'|'available'|'unavailable'  $status
     * @param  array<string, mixed>|null  $record
     */
    private function respond(string $status, ?array $record = null): JsonResponse
    {
        return response()->json(['data' => ['status' => $status, 'record' => $record]]);
    }
}
