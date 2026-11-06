<?php

namespace App\Http\Controllers\Orcid;

use App\Enums\OrcidSyncIntent;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrcidReviewContributionResource;
use App\Jobs\SyncReviewContributionWithOrcid;
use App\Models\Author;
use App\Models\ManuscriptReviewContributor;
use Illuminate\Database\Eloquent\Collection;
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

        return $this->list($author, $this->eligibleContributions($author));
    }

    /**
     * Add every eligible contribution the author has not decided on yet to
     * their ORCID record. Declined contributions are left off.
     */
    public function addAll(Request $request): JsonResource
    {
        $author = $request->user()->author;
        abort_if($author === null, 404);

        $this->eligibleContributions($author)
            ->filter(fn (ManuscriptReviewContributor $contribution): bool => $contribution->orcid_sync === null)
            ->each(function (ManuscriptReviewContributor $contribution) use ($request): void {
                $this->recordIntent($request, $contribution, OrcidSyncIntent::WANTED);
                dispatch(new SyncReviewContributionWithOrcid($contribution));
            });

        // reloaded: the syncs may already have run
        return $this->list($author, $this->eligibleContributions($author));
    }

    /**
     * The author wants the contribution on their ORCID record: it is added now,
     * or once they connect ORCID with the write scope.
     */
    public function add(Request $request, ManuscriptReviewContributor $manuscriptReviewContributor): JsonResource
    {
        return $this->changeIntent($request, $manuscriptReviewContributor, OrcidSyncIntent::WANTED);
    }

    /**
     * The author no longer wants the contribution on their ORCID record: it is
     * removed now, or once they connect ORCID with the write scope.
     */
    public function remove(Request $request, ManuscriptReviewContributor $manuscriptReviewContributor): JsonResource
    {
        return $this->changeIntent($request, $manuscriptReviewContributor, OrcidSyncIntent::DECLINED);
    }

    /**
     * @return Collection<int, ManuscriptReviewContributor>
     */
    private function eligibleContributions(Author $author): Collection
    {
        return $author->reviewContributions()
            ->with('manuscriptRecord.publication.journal')
            ->latest('review_date')
            ->get()
            ->filter(fn (ManuscriptReviewContributor $contribution): bool => $contribution->isEligibleForOrcid())
            ->values();
    }

    /**
     * @param  Collection<int, ManuscriptReviewContributor>  $contributions
     */
    private function list(Author $author, Collection $contributions): JsonResource
    {
        return OrcidReviewContributionResource::collection($contributions)->additional([
            'meta' => [
                'can_write_to_orcid' => $author->hasValidOrcidCredentials(),
            ],
        ]);
    }

    private function changeIntent(Request $request, ManuscriptReviewContributor $contribution, OrcidSyncIntent $intent): JsonResource
    {
        $author = $request->user()->author;
        abort_if($author === null || $contribution->author_id !== $author->id, 403);

        // removing never depends on eligibility: what is on ORCID can always be taken off
        $contribution->load('manuscriptRecord.publication.journal');
        abort_if($intent === OrcidSyncIntent::WANTED && ! $contribution->isEligibleForOrcid(), 422, 'This contribution cannot be credited on ORCID.');

        if ($contribution->orcid_sync !== $intent) {
            $this->recordIntent($request, $contribution, $intent);
        }

        dispatch(new SyncReviewContributionWithOrcid($contribution));

        return OrcidReviewContributionResource::make($contribution->refresh());
    }

    private function recordIntent(Request $request, ManuscriptReviewContributor $contribution, OrcidSyncIntent $intent): void
    {
        $contribution->orcid_sync = $intent;
        $contribution->save();

        activity()
            ->performedOn($contribution->manuscriptRecord)
            ->withProperties(['author_id' => $contribution->author_id, 'orcid_sync' => $intent->value])
            ->causedBy($request->user())
            ->log(match ($intent) {
                OrcidSyncIntent::WANTED => 'manuscript.review-contributor.orcid-wanted',
                OrcidSyncIntent::DECLINED => 'manuscript.review-contributor.orcid-declined',
            });
    }
}
