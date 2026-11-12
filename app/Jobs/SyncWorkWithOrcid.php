<?php

namespace App\Jobs;

use App\Enums\OrcidSyncIntent;
use App\Models\Author;
use App\Models\Publication;
use App\Models\PublicationAuthor;
use App\Orcid\Exceptions\AuthorNotConnectedToOrcid;
use App\Orcid\Exceptions\WorkNotSyncable;
use App\Orcid\Mappers\WorkMapper;
use App\Orcid\OrcidClientFactory;
use App\Orcid\OrcidErrors;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use VincentAuger\OrcidSdk\Data\Works\WorkInput;
use VincentAuger\OrcidSdk\Exceptions\OrcidConflictException;
use VincentAuger\OrcidSdk\Exceptions\OrcidException;
use VincentAuger\OrcidSdk\Exceptions\OrcidForbiddenException;
use VincentAuger\OrcidSdk\Exceptions\OrcidNotFoundException;
use VincentAuger\OrcidSdk\Exceptions\OrcidRequestException;
use VincentAuger\OrcidSdk\Resources\WorksResource;

/**
 * Brings a publication author's ORCID work in line with what the author wants
 * and with OSP, based on the state of the publication author when the job runs:
 *
 * - removed from the publication: delete the work on ORCID (if it was ever
 *   synced), then remove the publication author from OSP
 * - declined or no longer eligible (including a deleted publication) but on
 *   ORCID: delete it there and clear the put-code, keeping the intent so a
 *   restored publication is added again
 * - synced before: update it on ORCID; if the author deleted it there, they
 *   no longer want it: mark it declined instead of creating it again
 * - never synced: create it on ORCID and store the put-code
 *
 * When the author is not connected to ORCID, the work stays pending. Pending
 * works are synced again when the author reconnects (see dispatchPendingFor()).
 */
class SyncWorkWithOrcid implements ShouldQueue
{
    use Queueable;

    /**
     * Retries only happen for temporary ORCID failures (outages, rate limits,
     * connection errors); other errors fail the job immediately.
     */
    public int $tries = 5;

    /**
     * Another job for the same publication author (e.g. dispatched on reconnect)
     * may already have removed it for good: nothing is left to sync.
     */
    public bool $deleteWhenMissingModels = true;

    public function __construct(public PublicationAuthor $publicationAuthor) {}

    /**
     * @return int[]
     */
    public function backoff(): array
    {
        return [60, 300, 900, 3600];
    }

    /**
     * Never sync the same work twice at the same time, which could create
     * duplicate works on ORCID.
     *
     * @return object[]
     */
    public function middleware(): array
    {
        return [new WithoutOverlapping((string) $this->publicationAuthor->id)->releaseAfter(30)->expireAfter(300)];
    }

    /**
     * Dispatch a sync for every work of the author that is out of sync, e.g.
     * after the author (re)connects their ORCID record.
     *
     * @return int The number of jobs dispatched
     */
    public static function dispatchPendingFor(Author $author): int
    {
        $pending = $author->publicationAuthors()
            ->withTrashed()
            ->where(fn (Builder $query): Builder => $query->whereNotNull('orcid_sync')->orWhereNotNull('deleted_at'))
            ->with('publication.journal')
            ->get()
            ->filter(fn (PublicationAuthor $publicationAuthor): bool => $publicationAuthor->isRemovedFromPublication()
                || $publicationAuthor->needsRemovalFromOrcid()
                || $publicationAuthor->needsSyncWithOrcid());

        $pending->each(fn (PublicationAuthor $publicationAuthor): PendingDispatch => dispatch(new self($publicationAuthor)));

        return $pending->count();
    }

    public function handle(OrcidClientFactory $orcid): void
    {
        $publicationAuthor = $this->publicationAuthor;

        // never synced and now removed: nothing to delete on ORCID
        if ($publicationAuthor->isRemovedFromPublication() && ! $publicationAuthor->isSyncedWithOrcid()) {
            $publicationAuthor->forceDelete();

            return;
        }

        if (! $publicationAuthor->needsRemovalFromOrcid() && ! $publicationAuthor->needsSyncWithOrcid()) {
            return;
        }

        try {
            $works = $orcid->forAuthor($publicationAuthor->author)->works();

            if ($publicationAuthor->needsRemovalFromOrcid()) {
                $this->delete($works, $publicationAuthor);
            } else {
                $this->createOrUpdate($works, $publicationAuthor);
            }
        } catch (AuthorNotConnectedToOrcid|WorkNotSyncable $exception) {
            Log::info('ORCID sync pending: '.$exception->getMessage());
        } catch (OrcidException $exception) {
            $this->handleOrcidFailure($exception);
        }
    }

    private function createOrUpdate(WorksResource $works, PublicationAuthor $publicationAuthor): void
    {
        $input = WorkMapper::toWorkInput($publicationAuthor);

        if ($publicationAuthor->isSyncedWithOrcid()) {
            try {
                $works->update($publicationAuthor->orcid_putcode, $input);
            } catch (OrcidNotFoundException) {
                $this->markDeletedOnOrcid($publicationAuthor);

                return;
            }

            $this->markSynced($publicationAuthor, $input, created: false);

            return;
        }

        try {
            $publicationAuthor->orcid_putcode = $works->create($input);
        } catch (OrcidConflictException $exception) {
            // created by an earlier attempt whose put-code was never stored: adopt it
            if ($exception->existingPutCode() === null) {
                throw $exception;
            }

            $publicationAuthor->orcid_putcode = $exception->existingPutCode();
            $works->update($publicationAuthor->orcid_putcode, $input);
        }

        $this->markSynced($publicationAuthor, $input, created: true);
    }

    /**
     * Store the put-code and mark the work as synced, unless the publication
     * author changed while ORCID was being updated (the publication edited, the
     * work declined or the author removed): what ORCID has is then out of date,
     * so it stays pending for the job dispatched with that change. The put-code
     * is always stored, so that job updates or deletes the work instead of
     * creating a duplicate or leaving it.
     */
    private function markSynced(PublicationAuthor $sent, WorkInput $input, bool $created): void
    {
        $changed = DB::transaction(function () use ($sent, $input): bool {
            $current = PublicationAuthor::withTrashed()->lockForUpdate()->findOrFail($sent->id);
            $changed = $current->trashed() || ! $current->isWantedOnOrcid() || ! $this->wouldSend($current, $input);

            $current->orcid_putcode = $sent->orcid_putcode;

            if (! $changed) {
                $current->orcid_updated_at = now();
            }

            // syncing is not a change to the publication author: keep its updated_at
            PublicationAuthor::withoutTimestamps(fn () => $current->save());

            return $changed;
        });

        if ($created) {
            $this->logOnPublication($sent, 'publication.author.orcid-created', ['put_code' => $sent->orcid_putcode]);
        }

        Log::info($changed ? 'ORCID work changed while syncing, left pending' : 'ORCID work synced', [
            'publication_author_id' => $sent->id,
            'put_code' => $sent->orcid_putcode,
        ]);
    }

    /**
     * Whether the publication author, as it is now, maps to what was sent to ORCID
     */
    private function wouldSend(PublicationAuthor $publicationAuthor, WorkInput $input): bool
    {
        try {
            return WorkMapper::toWorkInput($publicationAuthor) == $input;
        } catch (WorkNotSyncable) {
            return false;
        }
    }

    /**
     * An author removed from the publication is then removed from OSP. Otherwise
     * only the put-code is cleared: if the author wanted the work again, or it
     * became eligible again (e.g. the publication was restored), the job
     * dispatched with that change creates it.
     */
    private function delete(WorksResource $works, PublicationAuthor $publicationAuthor): void
    {
        try {
            $works->delete($publicationAuthor->orcid_putcode);
        } catch (OrcidNotFoundException) {
            // already deleted on ORCID
        }

        $putCode = $publicationAuthor->orcid_putcode;

        DB::transaction(function () use ($publicationAuthor): void {
            $current = PublicationAuthor::withTrashed()->lockForUpdate()->findOrFail($publicationAuthor->id);

            if ($current->isRemovedFromPublication()) {
                $current->forceDelete();

                return;
            }

            $current->orcid_putcode = null;
            $current->orcid_updated_at = null;

            // syncing is not a change to the publication author: keep its updated_at
            PublicationAuthor::withoutTimestamps(fn () => $current->save());
        });

        $this->logOnPublication($publicationAuthor, 'publication.author.orcid-deleted', ['put_code' => $putCode]);

        Log::info('ORCID work deleted', ['publication_author_id' => $publicationAuthor->id, 'put_code' => $putCode]);
    }

    /**
     * Only the author can delete a work OSP added to their ORCID record: they no
     * longer want it there, so it is never added again, even if the publication
     * was edited meanwhile. Unless the author decided again in OSP since the job
     * loaded it, e.g. added it again: the job dispatched with that decision then
     * creates it.
     */
    private function markDeletedOnOrcid(PublicationAuthor $publicationAuthor): void
    {
        DB::transaction(function () use ($publicationAuthor): void {
            $current = PublicationAuthor::withTrashed()->lockForUpdate()->findOrFail($publicationAuthor->id);

            if ($current->orcid_sync_at?->toDateTimeString() === $publicationAuthor->orcid_sync_at?->toDateTimeString()) {
                $current->orcid_sync = OrcidSyncIntent::DECLINED;
            }

            $current->orcid_putcode = null;
            $current->orcid_updated_at = null;

            // syncing is not a change to the publication author: keep its updated_at
            PublicationAuthor::withoutTimestamps(fn () => $current->save());
        });

        $this->logOnPublication($publicationAuthor, 'publication.author.orcid-deleted-by-author');

        Log::info('ORCID work deleted by the author, marked declined', ['publication_author_id' => $publicationAuthor->id]);
    }

    /**
     * Log an ORCID change on the work's publication, even a deleted one
     *
     * @param  array<string, mixed>  $properties
     */
    private function logOnPublication(PublicationAuthor $publicationAuthor, string $description, array $properties = []): void
    {
        activity()
            ->performedOn(Publication::withTrashed()->findOrFail($publicationAuthor->publication_id))
            ->withProperties(['author_id' => $publicationAuthor->author_id, ...$properties])
            ->log($description);
    }

    /**
     * Retry temporary failures; fail everything else without retrying.
     */
    private function handleOrcidFailure(OrcidException $exception): void
    {
        $context = [
            'publication_author_id' => $this->publicationAuthor->id,
            'error' => $exception->getMessage(),
        ];

        if ($exception->isRetryable()) {
            Log::warning('ORCID sync failed, will retry', $context);

            throw $exception;
        }

        // the token was revoked, expired or lacks the write scope: the author must reconnect
        if (OrcidErrors::revokesAccess($exception)) {
            Log::warning('ORCID token no longer valid, disconnecting the author', $context);
            $this->publicationAuthor->author->clearOrcidToken();
        } elseif ($exception instanceof OrcidForbiddenException && $exception->isNotSource()) {
            // OSP only stores put-codes it created: this points to a misconfiguration,
            // e.g. a put-code created with another environment's ORCID client
            Log::error('ORCID refused the change: this client is not the source of the work', $context);
        } else {
            Log::error('ORCID rejected the work', [
                ...$context,
                'developer_message' => $exception instanceof OrcidRequestException ? $exception->developerMessage : null,
            ]);
        }

        $this->fail($exception);
    }
}
