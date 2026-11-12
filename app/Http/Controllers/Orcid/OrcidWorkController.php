<?php

namespace App\Http\Controllers\Orcid;

use App\Enums\OrcidSyncIntent;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrcidWorkResource;
use App\Jobs\SyncWorkWithOrcid;
use App\Models\Author;
use App\Models\PublicationAuthor;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The authenticated user's own publications that can be on their ORCID record as works.
 */
class OrcidWorkController extends Controller
{
    /**
     * List the eligible publications of the user's author.
     */
    public function index(Request $request): JsonResource
    {
        $author = $request->user()->author;
        abort_if($author === null, 404);

        return $this->list($author, $this->eligibleWorks($author));
    }

    /**
     * Add every eligible publication the author has not decided on yet to
     * their ORCID record. Declined publications are left off.
     */
    public function addAll(Request $request): JsonResource
    {
        $author = $request->user()->author;
        abort_if($author === null, 404);

        $this->eligibleWorks($author)
            ->filter(fn (PublicationAuthor $publicationAuthor): bool => $publicationAuthor->orcid_sync === null)
            ->each(function (PublicationAuthor $publicationAuthor) use ($request): void {
                $publicationAuthor->recordOrcidIntent(OrcidSyncIntent::WANTED, $request->user());
                dispatch(new SyncWorkWithOrcid($publicationAuthor));
            });

        // reloaded: the syncs may already have run
        return $this->list($author, $this->eligibleWorks($author));
    }

    /**
     * Whether the author's publications are added to ORCID as soon as they are
     * published. Only future publications: turning it on leaves the backlog to
     * "Add all", turning it off leaves ORCID as it is.
     */
    public function updateAutoSync(Request $request): JsonResponse
    {
        $author = $request->user()->author;
        abort_if($author === null, 404);

        $validated = $request->validate([
            'auto_sync' => ['required', 'boolean'],
        ]);

        $author->update(['orcid_auto_sync_works' => $validated['auto_sync']]);

        return response()->json(['auto_sync' => $author->orcid_auto_sync_works]);
    }

    /**
     * The author wants the publication on their ORCID record: it is added now,
     * or once they connect ORCID with the write scope.
     */
    public function add(Request $request, PublicationAuthor $publicationAuthor): JsonResource
    {
        return $this->changeIntent($request, $publicationAuthor, OrcidSyncIntent::WANTED);
    }

    /**
     * The author no longer wants the publication on their ORCID record: it is
     * removed now, or once they connect ORCID with the write scope.
     */
    public function remove(Request $request, PublicationAuthor $publicationAuthor): JsonResource
    {
        return $this->changeIntent($request, $publicationAuthor, OrcidSyncIntent::DECLINED);
    }

    /**
     * @return Collection<int, PublicationAuthor>
     */
    private function eligibleWorks(Author $author): Collection
    {
        return $author->publicationAuthors()
            ->with('publication.journal')
            ->get()
            ->filter(fn (PublicationAuthor $publicationAuthor): bool => $publicationAuthor->isEligibleForOrcid())
            ->sortByDesc(fn (PublicationAuthor $publicationAuthor): string => $publicationAuthor->publication->published_on?->toDateString() ?? '')
            ->values();
    }

    /**
     * @param  Collection<int, PublicationAuthor>  $works
     */
    private function list(Author $author, Collection $works): JsonResource
    {
        return OrcidWorkResource::collection($works)->additional([
            'meta' => [
                'can_write_to_orcid' => $author->hasValidOrcidCredentials(),
                'auto_sync' => $author->orcid_auto_sync_works,
            ],
        ]);
    }

    private function changeIntent(Request $request, PublicationAuthor $publicationAuthor, OrcidSyncIntent $intent): JsonResource
    {
        $author = $request->user()->author;
        abort_if($author === null || $publicationAuthor->author_id !== $author->id, 403);

        // removing never depends on eligibility: what is on ORCID can always be taken off
        $publicationAuthor->load('publication.journal');
        abort_if($intent === OrcidSyncIntent::WANTED && ! $publicationAuthor->isEligibleForOrcid(), 422, 'This publication cannot be added to ORCID.');

        if ($publicationAuthor->orcid_sync !== $intent) {
            $publicationAuthor->recordOrcidIntent($intent, $request->user());
        }

        dispatch(new SyncWorkWithOrcid($publicationAuthor));

        return OrcidWorkResource::make($publicationAuthor->refresh());
    }
}
