<?php

namespace App\Jobs;

use App\Enums\OrcidSyncIntent;
use App\Models\Author;
use App\Models\ManuscriptReviewContributor;
use App\Orcid\Exceptions\AuthorNotConnectedToOrcid;
use App\Orcid\Exceptions\ReviewContributionNotSyncable;
use App\Orcid\Mappers\PeerReviewMapper;
use App\Orcid\OrcidClientFactory;
use App\Orcid\OrcidErrors;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use VincentAuger\OrcidSdk\Data\PeerReviews\PeerReviewInput;
use VincentAuger\OrcidSdk\Exceptions\OrcidConflictException;
use VincentAuger\OrcidSdk\Exceptions\OrcidException;
use VincentAuger\OrcidSdk\Exceptions\OrcidForbiddenException;
use VincentAuger\OrcidSdk\Exceptions\OrcidNotFoundException;
use VincentAuger\OrcidSdk\Exceptions\OrcidRequestException;
use VincentAuger\OrcidSdk\Resources\PeerReviewsResource;

/**
 * Brings a review contribution the author wants on ORCID and its ORCID peer
 * review in line, based on the state of the contribution when the job runs:
 *
 * - synced before: update it on ORCID; if the author deleted it there, they
 *   no longer want it: mark it declined instead of creating it again
 * - never synced: create it on ORCID and store the put-code
 *
 * When the author is not connected to ORCID, or the default organization lacks
 * data ORCID requires, the contribution stays pending. Pending contributions are
 * synced again when the author reconnects (see dispatchPendingFor()).
 */
class SyncReviewContributionWithOrcid implements ShouldQueue
{
    use Queueable;

    /**
     * Retries only happen for temporary ORCID failures (outages, rate limits,
     * connection errors); other errors fail the job immediately.
     */
    public int $tries = 5;

    public function __construct(public ManuscriptReviewContributor $contributor) {}

    /**
     * @return int[]
     */
    public function backoff(): array
    {
        return [60, 300, 900, 3600];
    }

    /**
     * Never sync the same contribution twice at the same time, which could
     * create duplicate items on ORCID.
     *
     * @return object[]
     */
    public function middleware(): array
    {
        return [new WithoutOverlapping((string) $this->contributor->id)->releaseAfter(30)->expireAfter(300)];
    }

    /**
     * Dispatch a sync for every review contribution of the author that is out
     * of sync, e.g. after the author (re)connects their ORCID record.
     *
     * @return int The number of jobs dispatched
     */
    public static function dispatchPendingFor(Author $author): int
    {
        $pending = $author->reviewContributions()
            ->where('orcid_sync', OrcidSyncIntent::WANTED)
            ->with('manuscriptRecord.publication.journal')
            ->get()
            ->filter(fn (ManuscriptReviewContributor $contributor): bool => $contributor->needsSyncWithOrcid());

        $pending->each(fn (ManuscriptReviewContributor $contributor): PendingDispatch => dispatch(new self($contributor)));

        return $pending->count();
    }

    public function handle(OrcidClientFactory $orcid): void
    {
        $contributor = $this->contributor;

        if (! $contributor->needsSyncWithOrcid()) {
            return;
        }

        try {
            $this->createOrUpdate($orcid->forAuthor($contributor->author)->peerReviews(), $contributor);
        } catch (AuthorNotConnectedToOrcid|ReviewContributionNotSyncable $exception) {
            Log::info('ORCID sync pending: '.$exception->getMessage());
        } catch (OrcidException $exception) {
            $this->handleOrcidFailure($exception);
        }
    }

    private function createOrUpdate(PeerReviewsResource $peerReviews, ManuscriptReviewContributor $contributor): void
    {
        $input = PeerReviewMapper::toPeerReviewInput($contributor);

        if ($contributor->isSyncedWithOrcid()) {
            try {
                $peerReviews->update($contributor->orcid_putcode, $input);
            } catch (OrcidNotFoundException) {
                $this->markDeletedOnOrcid($contributor);

                return;
            }

            $this->markSynced($contributor, $input, created: false);

            return;
        }

        try {
            $contributor->orcid_putcode = $peerReviews->create($input);
        } catch (OrcidConflictException $exception) {
            // created by an earlier attempt whose put-code was never stored: adopt it
            if ($exception->existingPutCode() === null) {
                throw $exception;
            }

            $contributor->orcid_putcode = $exception->existingPutCode();
            $peerReviews->update($contributor->orcid_putcode, $input);
        }

        $this->markSynced($contributor, $input, created: true);
    }

    /**
     * Store the put-code and mark the contribution as synced, unless it changed
     * while ORCID was being updated: what ORCID has is then out of date, so the
     * contribution stays pending for the job dispatched with that change. The
     * put-code is always stored, so that job updates the item instead of
     * creating a duplicate.
     */
    private function markSynced(ManuscriptReviewContributor $sent, PeerReviewInput $input, bool $created): void
    {
        $changed = DB::transaction(function () use ($sent, $input): bool {
            $current = ManuscriptReviewContributor::query()->lockForUpdate()->findOrFail($sent->id);
            $changed = ! $current->isWantedOnOrcid() || ! $this->wouldSend($current, $input);

            $current->orcid_putcode = $sent->orcid_putcode;

            if (! $changed) {
                $current->orcid_updated_at = now();
            }

            // syncing is not a change to the contribution: keep its updated_at
            ManuscriptReviewContributor::withoutTimestamps(fn () => $current->save());

            return $changed;
        });

        if ($created) {
            activity()
                ->performedOn($sent->manuscriptRecord)
                ->withProperties(['author_id' => $sent->author_id, 'put_code' => $sent->orcid_putcode])
                ->log('manuscript.review-contributor.orcid-created');
        }

        Log::info($changed ? 'ORCID peer review changed while syncing, left pending' : 'ORCID peer review synced', [
            'manuscript_review_contributor_id' => $sent->id,
            'put_code' => $sent->orcid_putcode,
        ]);
    }

    /**
     * Whether the contribution, as it is now, maps to what was sent to ORCID
     */
    private function wouldSend(ManuscriptReviewContributor $contributor, PeerReviewInput $input): bool
    {
        try {
            return PeerReviewMapper::toPeerReviewInput($contributor) == $input;
        } catch (ReviewContributionNotSyncable) {
            return false;
        }
    }

    /**
     * Only the author can delete a peer review on ORCID: they no longer want it
     * there, so it is never added again.
     */
    private function markDeletedOnOrcid(ManuscriptReviewContributor $contributor): void
    {
        $contributor->orcid_sync = OrcidSyncIntent::DECLINED;
        $contributor->orcid_putcode = null;
        $contributor->orcid_updated_at = null;
        $contributor->save();

        activity()
            ->performedOn($contributor->manuscriptRecord)
            ->withProperties(['author_id' => $contributor->author_id])
            ->log('manuscript.review-contributor.orcid-deleted-by-author');

        Log::info('ORCID peer review deleted by the author, marked declined', ['manuscript_review_contributor_id' => $contributor->id]);
    }

    /**
     * Retry temporary failures; fail everything else without retrying.
     */
    private function handleOrcidFailure(OrcidException $exception): void
    {
        $context = [
            'manuscript_review_contributor_id' => $this->contributor->id,
            'error' => $exception->getMessage(),
        ];

        if ($exception->isRetryable()) {
            Log::warning('ORCID sync failed, will retry', $context);

            throw $exception;
        }

        // the token was revoked, expired or lacks the write scope: the author must reconnect
        if (OrcidErrors::revokesAccess($exception)) {
            Log::warning('ORCID token no longer valid, disconnecting the author', $context);
            $this->contributor->author->clearOrcidToken();
        } elseif ($exception instanceof OrcidForbiddenException && $exception->isNotSource()) {
            // OSP only stores put-codes it created: this points to a misconfiguration,
            // e.g. a put-code created with another environment's ORCID client
            Log::error('ORCID refused the change: this client is not the source of the item', $context);
        } else {
            Log::error('ORCID rejected the peer review', [
                ...$context,
                'developer_message' => $exception instanceof OrcidRequestException ? $exception->developerMessage : null,
            ]);
        }

        $this->fail($exception);
    }
}
