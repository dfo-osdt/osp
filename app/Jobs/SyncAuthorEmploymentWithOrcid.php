<?php

namespace App\Jobs;

use App\Models\Author;
use App\Models\AuthorEmployment;
use App\Orcid\Exceptions\AuthorNotConnectedToOrcid;
use App\Orcid\Exceptions\EmploymentNotSyncable;
use App\Orcid\Mappers\EmploymentMapper;
use App\Orcid\OrcidClientFactory;
use App\Orcid\OrcidErrors;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use VincentAuger\OrcidSdk\Data\Affiliations\AffiliationInput;
use VincentAuger\OrcidSdk\Enums\AffiliationType;
use VincentAuger\OrcidSdk\Exceptions\OrcidException;
use VincentAuger\OrcidSdk\Exceptions\OrcidForbiddenException;
use VincentAuger\OrcidSdk\Exceptions\OrcidNotFoundException;
use VincentAuger\OrcidSdk\Exceptions\OrcidRequestException;
use VincentAuger\OrcidSdk\Resources\AffiliationsResource;

/**
 * Brings an author employment and its ORCID employment item in line, based on
 * the state of the employment when the job runs:
 *
 * - deleted: delete it on ORCID (if it was ever synced), then remove it from OSP
 * - synced before: update it on ORCID (recreate it if the author deleted it there)
 * - never synced: create it on ORCID and store the put-code
 *
 * When the author is not connected to ORCID, or the organization lacks data ORCID
 * requires, the employment stays pending. Pending employments are synced again
 * when the author reconnects (see dispatchPendingFor()).
 */
class SyncAuthorEmploymentWithOrcid implements ShouldQueue
{
    use Queueable;

    /**
     * Retries only happen for temporary ORCID failures (outages, rate limits,
     * connection errors); other errors fail the job immediately.
     */
    public int $tries = 5;

    public function __construct(public AuthorEmployment $authorEmployment) {}

    /**
     * @return int[]
     */
    public function backoff(): array
    {
        return [60, 300, 900, 3600];
    }

    /**
     * Never sync the same employment twice at the same time (e.g. created then
     * quickly updated), which could create duplicate items on ORCID.
     *
     * @return object[]
     */
    public function middleware(): array
    {
        return [new WithoutOverlapping((string) $this->authorEmployment->id)->releaseAfter(30)->expireAfter(300)];
    }

    /**
     * Dispatch a sync for every employment of the author that is out of sync,
     * e.g. after the author (re)connects their ORCID record.
     *
     * @return int The number of jobs dispatched
     */
    public static function dispatchPendingFor(Author $author): int
    {
        $pending = $author->employments()->withTrashed()->get()->filter(
            fn (AuthorEmployment $employment): bool => $employment->trashed()
                ? $employment->isSyncedWithOrcid()
                : $employment->needsSyncWithOrcid(),
        );

        $pending->each(fn (AuthorEmployment $employment): PendingDispatch => dispatch(new self($employment)));

        return $pending->count();
    }

    public function handle(OrcidClientFactory $orcid): void
    {
        $employment = $this->authorEmployment;

        // never synced and now deleted: nothing to remove on ORCID
        if ($employment->trashed() && ! $employment->isSyncedWithOrcid()) {
            $employment->forceDelete();

            return;
        }

        if (! $employment->trashed() && ! $employment->needsSyncWithOrcid()) {
            return;
        }

        try {
            $affiliations = $orcid->forAuthor($employment->author)->affiliations();

            if ($employment->trashed()) {
                $this->delete($affiliations, $employment);
            } else {
                $this->createOrUpdate($affiliations, $employment);
            }
        } catch (AuthorNotConnectedToOrcid|EmploymentNotSyncable $exception) {
            Log::info('ORCID sync pending: '.$exception->getMessage());
        } catch (OrcidException $exception) {
            $this->handleOrcidFailure($exception);
        }
    }

    private function createOrUpdate(AffiliationsResource $affiliations, AuthorEmployment $employment): void
    {
        $input = EmploymentMapper::toAffiliationInput($employment);

        if ($employment->isSyncedWithOrcid()) {
            try {
                $affiliations->update(AffiliationType::Employment, $employment->orcid_putcode, $input);
            } catch (OrcidNotFoundException) {
                // the author deleted it on ORCID: add it again
                Log::info("ORCID employment {$employment->orcid_putcode} not found, creating it again", ['author_employment_id' => $employment->id]);
                $employment->orcid_putcode = $affiliations->create(AffiliationType::Employment, $input);
            }
        } else {
            $employment->orcid_putcode = $affiliations->create(AffiliationType::Employment, $input);
        }

        $this->markSynced($employment, $input);
    }

    /**
     * Store the put-code and mark the employment as synced, unless it changed
     * while ORCID was being updated (edited or deleted): what ORCID has is then
     * out of date, so the employment stays pending for the job dispatched with
     * that change. The put-code is always stored, so that job updates or deletes
     * the item instead of creating a duplicate.
     */
    private function markSynced(AuthorEmployment $sent, AffiliationInput $input): void
    {
        $changed = DB::transaction(function () use ($sent, $input): bool {
            $current = AuthorEmployment::withTrashed()->lockForUpdate()->findOrFail($sent->id);
            $changed = $current->trashed() || ! $this->wouldSend($current, $input);

            $current->orcid_putcode = $sent->orcid_putcode;

            if (! $changed) {
                $current->orcid_updated_at = now();
            }

            // syncing is not a change to the employment: keep its updated_at
            AuthorEmployment::withoutTimestamps(fn () => $current->save());

            return $changed;
        });

        Log::info($changed ? 'ORCID employment changed while syncing, left pending' : 'ORCID employment synced', [
            'author_employment_id' => $sent->id,
            'put_code' => $sent->orcid_putcode,
        ]);
    }

    /**
     * Whether the employment, as it is now, maps to what was sent to ORCID
     */
    private function wouldSend(AuthorEmployment $employment, AffiliationInput $input): bool
    {
        try {
            return EmploymentMapper::toAffiliationInput($employment) == $input;
        } catch (EmploymentNotSyncable) {
            return false;
        }
    }

    private function delete(AffiliationsResource $affiliations, AuthorEmployment $employment): void
    {
        try {
            $affiliations->delete(AffiliationType::Employment, $employment->orcid_putcode);
        } catch (OrcidNotFoundException) {
            // already deleted on ORCID
        }

        $employment->forceDelete();

        Log::info('ORCID employment deleted', ['author_employment_id' => $employment->id]);
    }

    /**
     * Retry temporary failures; fail everything else without retrying.
     */
    private function handleOrcidFailure(OrcidException $exception): void
    {
        $context = [
            'author_employment_id' => $this->authorEmployment->id,
            'error' => $exception->getMessage(),
        ];

        if ($exception->isRetryable()) {
            Log::warning('ORCID sync failed, will retry', $context);

            throw $exception;
        }

        // the token was revoked, expired or lacks the write scope: the author must reconnect
        if (OrcidErrors::revokesAccess($exception)) {
            Log::warning('ORCID token no longer valid, disconnecting the author', $context);
            $this->authorEmployment->author->clearOrcidToken();
        } elseif ($exception instanceof OrcidForbiddenException && $exception->isNotSource()) {
            // OSP only stores put-codes it created: this points to a misconfiguration,
            // e.g. a put-code created with another environment's ORCID client
            Log::error('ORCID refused the change: this client is not the source of the item', $context);
        } else {
            Log::error('ORCID rejected the employment', [
                ...$context,
                'developer_message' => $exception instanceof OrcidRequestException ? $exception->developerMessage : null,
            ]);
        }

        $this->fail($exception);
    }
}
