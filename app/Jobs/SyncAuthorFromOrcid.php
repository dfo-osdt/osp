<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Author;
use App\Orcid\EmploymentReconciler;
use App\Orcid\Exceptions\AuthorNotConnectedToOrcid;
use App\Orcid\OrcidErrors;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\Log;
use VincentAuger\OrcidSdk\Exceptions\OrcidException;

/**
 * Brings an author's OSP data in line with their ORCID record, in the
 * background (e.g. right after the author connects). See EmploymentReconciler.
 */
class SyncAuthorFromOrcid implements ShouldQueue
{
    use Queueable;

    /**
     * Retries only happen for temporary ORCID failures (outages, rate limits,
     * connection errors); other errors fail the job immediately.
     */
    public int $tries = 5;

    public function __construct(public Author $author) {}

    /**
     * @return int[]
     */
    public function backoff(): array
    {
        return [60, 300, 900, 3600];
    }

    /**
     * Never sync the same author twice at the same time, which could import
     * the same item twice.
     *
     * @return object[]
     */
    public function middleware(): array
    {
        return [new WithoutOverlapping("sync-from-orcid-{$this->author->id}")->releaseAfter(30)->expireAfter(300)];
    }

    public function handle(EmploymentReconciler $employments): void
    {
        try {
            $employments->reconcile($this->author);
        } catch (AuthorNotConnectedToOrcid $exception) {
            Log::info('ORCID sync from ORCID skipped: '.$exception->getMessage());
        } catch (OrcidException $exception) {
            if ($exception->isRetryable()) {
                throw $exception;
            }

            Log::error('ORCID record could not be read', ['author_id' => $this->author->id, 'error' => $exception->getMessage()]);

            if (OrcidErrors::revokesAccess($exception)) {
                $this->author->clearOrcidToken();
            }

            $this->fail($exception);
        }
    }
}
