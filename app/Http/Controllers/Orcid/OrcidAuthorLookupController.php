<?php

namespace App\Http\Controllers\Orcid;

use App\Orcid\OrcidAuthorLookup;
use App\Orcid\OrcidClientFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use VincentAuger\OrcidSdk\Exceptions\OrcidException;
use VincentAuger\OrcidSdk\Support\OrcidId;

/**
 * Search ORCID for someone to add as an author, then preview what their public
 * record fills in on the new author form. The SPA never talks to ORCID: every
 * link to it comes from here.
 *
 * The status is "unavailable" when ORCID cannot be read, so the author can
 * still be added by hand (or searched for on orcid.org).
 */
class OrcidAuthorLookupController
{
    public function __construct(private readonly OrcidAuthorLookup $lookup) {}

    public function search(Request $request, OrcidClientFactory $orcid): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:200'],
        ]);
        $query = $validated['q'];

        try {
            $found = ['status' => 'available', ...$this->lookup->search($query)];
        } catch (OrcidException $exception) {
            Log::warning('ORCID could not be searched for an author', ['error' => $exception->getMessage()]);

            $found = ['status' => 'unavailable', 'num_found' => 0, 'results' => []];
        }

        return response()->json(['data' => [
            ...$found,
            'orcid_search_url' => $orcid->searchUrl($query, app()->getLocale()),
        ]]);
    }

    public function preview(string $orcid): JsonResponse
    {
        abort_unless(OrcidId::isValid($orcid), 404);

        try {
            return response()->json(['data' => ['status' => 'available', 'author' => $this->lookup->preview($orcid)]]);
        } catch (OrcidException $exception) {
            Log::warning('ORCID record could not be read to preview an author', ['orcid' => $orcid, 'error' => $exception->getMessage()]);

            return response()->json(['data' => ['status' => 'unavailable', 'author' => null]]);
        }
    }
}
