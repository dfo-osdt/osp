<?php

namespace App\Http\Controllers\Orcid;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrcidReviewContributionResource;
use App\Models\ManuscriptReviewContributor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The authenticated user's own review contributions that can be credited on ORCID.
 */
class OrcidReviewContributionController extends Controller
{
    /**
     * List the eligible review contributions of the user's author.
     */
    public function index(Request $request): JsonResource
    {
        $author = $request->user()->author;
        abort_if($author === null, 404);

        $contributions = $author->reviewContributions()
            ->with('manuscriptRecord.publication.journal')
            ->latest('review_date')
            ->get()
            ->filter(fn (ManuscriptReviewContributor $contribution): bool => $contribution->isEligibleForOrcid())
            ->values();

        return OrcidReviewContributionResource::collection($contributions)->additional([
            'meta' => [
                'can_write_to_orcid' => $author->hasValidOrcidCredentials(),
            ],
        ]);
    }
}
