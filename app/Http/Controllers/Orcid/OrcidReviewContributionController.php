<?php

namespace App\Http\Controllers\Orcid;

use App\Enums\OrcidSyncIntent;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrcidReviewContributionResource;
use App\Jobs\SyncReviewContributionWithOrcid;
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

    /**
     * The author wants the contribution on their ORCID record: it is added now,
     * or once they connect ORCID with the write scope.
     */
    public function add(Request $request, ManuscriptReviewContributor $manuscriptReviewContributor): JsonResource
    {
        $author = $request->user()->author;
        abort_if($author === null || $manuscriptReviewContributor->author_id !== $author->id, 403);

        $manuscriptReviewContributor->load('manuscriptRecord.publication.journal');
        abort_unless($manuscriptReviewContributor->isEligibleForOrcid(), 422, 'This contribution cannot be added to ORCID.');

        if (! $manuscriptReviewContributor->isWantedOnOrcid()) {
            $manuscriptReviewContributor->orcid_sync = OrcidSyncIntent::WANTED;
            $manuscriptReviewContributor->save();

            activity()
                ->performedOn($manuscriptReviewContributor->manuscriptRecord)
                ->withProperties(['author_id' => $author->id, 'orcid_sync' => OrcidSyncIntent::WANTED->value])
                ->causedBy($request->user())
                ->log('manuscript.review-contributor.orcid-wanted');
        }

        dispatch(new SyncReviewContributionWithOrcid($manuscriptReviewContributor));

        return OrcidReviewContributionResource::make($manuscriptReviewContributor->refresh());
    }
}
