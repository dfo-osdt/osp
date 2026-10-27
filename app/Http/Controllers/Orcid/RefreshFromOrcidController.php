<?php

namespace App\Http\Controllers\Orcid;

use App\Jobs\SyncAuthorEmploymentWithOrcid;
use App\Orcid\EmploymentReconciler;
use App\Orcid\OrcidErrors;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use VincentAuger\OrcidSdk\Exceptions\OrcidException;

/**
 * Called when the author opens their ORCID settings: picks up what they changed
 * directly on their ORCID record (e.g. deleted an employment OSP created), then
 * pushes what is still pending, e.g. changes whose sync failed while ORCID was down.
 */
class RefreshFromOrcidController
{
    /**
     * Read ORCID at most once per author in this many seconds
     */
    private const int THROTTLE_SECONDS = 60;

    public function __invoke(EmploymentReconciler $employments): JsonResponse
    {
        $author = Auth::user()->author;

        if (! $author->hasValidOrcidCredentials()) {
            return response()->json(['refreshed' => false]);
        }

        if (! Cache::add("orcid-refresh:{$author->id}", true, self::THROTTLE_SECONDS)) {
            return response()->json(['refreshed' => false]);
        }

        try {
            $result = $employments->reconcile($author);
        } catch (OrcidException $exception) {
            Log::warning('ORCID record could not be read', ['author_id' => $author->id, 'error' => $exception->getMessage()]);

            if (OrcidErrors::revokesAccess($exception)) {
                $author->clearOrcidToken();
            }

            return response()->json(['refreshed' => false]);
        }

        $pending = SyncAuthorEmploymentWithOrcid::dispatchPendingFor($author);

        return response()->json(['refreshed' => true, 'employments' => $result, 'pending' => $pending]);
    }
}
