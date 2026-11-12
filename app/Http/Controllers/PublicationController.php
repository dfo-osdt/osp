<?php

namespace App\Http\Controllers;

use App\Actions\DeletePublication;
use App\Enums\OrcidSyncIntent;
use App\Enums\PublicationStatus;
use App\Events\PublicationPublished;
use App\Exports\PublicationsExport;
use App\Http\Resources\PublicationResource;
use App\Jobs\SyncReviewContributionWithOrcid;
use App\Jobs\SyncWorkWithOrcid;
use App\Mail\ReviewContributionPublishedMail;
use App\Mail\WorkPublishedMail;
use App\Models\ManuscriptReviewContributor;
use App\Models\Publication;
use App\Models\PublicationAuthor;
use App\Models\User;
use App\Queries\PublicationListQuery;
use App\Rules\Doi;
use App\Rules\Isbn;
use App\Rules\JournalMatchesManuscriptType;
use App\Rules\ValidTitleMarkup;
use App\Traits\PaginationLimitTrait;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class PublicationController extends Controller
{
    use PaginationLimitTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(#[CurrentUser] User $user, Request $request): ResourceCollection
    {
        $limit = $this->getLimitFromRequest($request);

        $baseQuery = Publication::query()
            ->forUser($user)
            ->with([
                'journal',
                'publicationAuthors' => fn ($q) => $q->with('author', 'organization')->chaperone('publication'),
                'region',
            ])->when($request->has('filter.functional_area_id'), fn ($q) => $q->with('manuscriptRecord'));

        $publicationListQuery = new PublicationListQuery($baseQuery, $request);

        return PublicationResource::collection($publicationListQuery->paginate($limit));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResource
    {
        // can this user create a publication?
        $this->authorize('create', Publication::class);

        // validate the request
        $validated = $request->validate([
            'status' => new Enum(PublicationStatus::class),
            'title' => ['required', 'string', new ValidTitleMarkup],
            'journal_id' => ['bail', 'required', 'integer', 'exists:journals,id', new JournalMatchesManuscriptType(manuscriptRecord: null)],
            'doi' => ['nullable', 'string', new Doi],
            'isbn' => ['nullable', 'string', new Isbn],
            'catalogue_number' => ['nullable', 'string'],
            'issue_number' => ['nullable', 'string'],
            'accepted_on' => ['date', 'nullable'],
            'published_on' => 'date|nullable|required_if:status,'.PublicationStatus::PUBLISHED->value,
            'embargoed_until' => ['date', 'nullable'],
            'is_open_access' => ['boolean'],
            'region_id' => ['required', 'exists:regions,id'],
        ]);

        if (isset($validated['is_open_access']) && $validated['is_open_access']) {
            $validated['embargoed_until'] = null;
        }

        // create the publication
        $publication = new Publication($validated);
        $publication->user_id = $request->user()->id;
        $publication->save();

        return $this->defaultResource($publication);
    }

    /**
     * Display the specified resource.
     */
    public function show(Publication $publication): JsonResource
    {
        $publication->load([
            'publicationAuthors' => fn ($q) => $q->with('author')->chaperone('publication'),
            'manuscriptRecord.manuscriptAuthors.author',
            'region',
        ]);
        Gate::authorize('view', $publication);

        return $this->defaultResource($publication);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Publication $publication): JsonResource
    {
        $publication->load([
            'publicationAuthors' => fn ($q) => $q->with('author')->chaperone('publication'),
            'region',
        ]);
        Gate::authorize('update', $publication);

        // check if user is seeking to change the status from accepted to published
        if (isset($request['status'])) {
            $validatedStatus = $request->validate([
                'status' => new Enum(PublicationStatus::class),
            ]);
            if ($validatedStatus['status'] !== $publication->status->value) {
                switch ($validatedStatus['status']) {
                    case PublicationStatus::PUBLISHED->value:
                        Gate::authorize('publish', $publication);
                        $publication->status = PublicationStatus::PUBLISHED;
                        $request['accepted_on'] ??= $publication->accepted_on;
                        break;
                    default:
                        // likely, only way we're here is someone is having a go at the API directly.
                        throw ValidationException::withMessages(['status' => 'Cannot change status back to accepted']);
                }
            }
        }

        // validate the rest of the request
        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', new ValidTitleMarkup],
            'journal_id' => ['bail', 'sometimes', 'required', 'integer', 'exists:journals,id', Rule::when(
                $request->integer('journal_id') !== $publication->journal_id,
                [new JournalMatchesManuscriptType($publication->manuscriptRecord)]
            )],
            'doi' => ['string', 'nullable', new Doi],
            'isbn' => ['nullable', 'string', new Isbn],
            'catalogue_number' => ['nullable', 'string'],
            'issue_number' => ['nullable', 'string'],
            'accepted_on' => ['date', 'nullable', Rule::when($publication->status === PublicationStatus::PUBLISHED, ['before_or_equal:published_on']), Rule::requiredIf($publication->status === PublicationStatus::ACCEPTED)],
            'published_on' => ['date', 'nullable', 'after_or_equal:accepted_on', Rule::requiredIf($publication->status === PublicationStatus::PUBLISHED)],
            'embargoed_until' => ['date', 'nullable'],
            'is_open_access' => ['boolean'],
            'region_id' => ['sometimes', 'required', 'exists:regions,id'],
        ]);

        if (isset($validated['is_open_access']) && $validated['is_open_access']) {
            $validated['embargoed_until'] = null;
        }

        // update the publication
        $publication->update($validated);

        // the review contributions' ORCID peer reviews are built from these
        if ($publication->manuscriptRecord !== null && $publication->wasChanged(['title', 'doi', 'journal_id'])) {
            SyncReviewContributionWithOrcid::dispatchForChanged($publication->manuscriptRecord->reviewContributors);
        }

        // and the authors' ORCID works from these
        if ($publication->wasChanged(['title', 'doi', 'published_on', 'journal_id', 'status'])) {
            SyncWorkWithOrcid::dispatchForChanged($publication->publicationAuthors);
        }

        if ($publication->wasChanged('status') && $publication->status === PublicationStatus::PUBLISHED) {
            $this->offerWorksForOrcid($publication, $request->user());

            if ($publication->manuscriptRecord !== null) {
                $this->offerReviewContributionsForOrcid($publication, $request->user());
                event(new PublicationPublished($publication, $request->user()));
            }
        }

        return $this->defaultResource($publication);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publication $publication): Response
    {
        Gate::authorize('delete', $publication);

        DeletePublication::handle($publication);

        return response()->noContent();
    }

    /**
     * Export all matching publications as RIS or Excel.
     */
    public function export(#[CurrentUser] User $user, Request $request)
    {
        $baseQuery = Publication::query()
            ->forUser($user)
            ->with([
                'journal',
                'publicationAuthors' => fn ($q) => $q->with('author', 'organization')->chaperone('publication'),
                'region',
            ]);

        $query = new PublicationListQuery($baseQuery, $request);

        if ($request->input('format') === 'excel') {
            return Excel::download(new PublicationsExport($query), 'publications.xlsx');
        }

        if ($request->input('format') === 'bibtex') {
            return response()->streamDownload(function () use ($query): void {
                $query->lazy()->each(function (Publication $publication): void {
                    echo $publication->toBibtex()."\n";
                });
            }, 'publications.bib', ['Content-Type' => 'application/x-bibtex']);
        }

        return response()->streamDownload(function () use ($query): void {
            $query->lazy()->each(function (Publication $publication): void {
                echo $publication->toRis()."\r\n";
            });
        }, 'publications.ris', ['Content-Type' => 'application/x-research-info-systems']);
    }

    /**
     * Now that the publication is published, add to ORCID the review contributions
     * of authors with auto sync on, and email the other authors that they can add
     * theirs. Only authors with a user account, for contributions eligible right
     * now that they have not decided on: those becoming eligible later are left to
     * "Add all".
     */
    private function offerReviewContributionsForOrcid(Publication $publication, User $publisher): void
    {
        $publication->manuscriptRecord->reviewContributors()
            ->whereNull('orcid_sync')
            ->whereHas('author.user')
            ->with('author.user', 'manuscriptRecord.publication.journal')
            ->get()
            ->filter(fn (ManuscriptReviewContributor $contributor): bool => $contributor->isEligibleForOrcid())
            ->each(function (ManuscriptReviewContributor $contributor) use ($publisher): void {
                if ($contributor->author->orcid_auto_sync_reviews) {
                    $contributor->recordOrcidIntent(OrcidSyncIntent::WANTED, $publisher, byAutoSync: true);
                    dispatch(new SyncReviewContributionWithOrcid($contributor));
                } else {
                    Mail::queue(new ReviewContributionPublishedMail($contributor));
                }
            });
    }

    /**
     * Now that the publication is published, add it to the ORCID record of its
     * authors with auto sync on, and email the other authors that they can add it.
     * Only authors with a user account who have not decided, and only when the
     * publication is eligible right now: if it becomes eligible later, it is left
     * to "Add all".
     */
    private function offerWorksForOrcid(Publication $publication, User $publisher): void
    {
        $publication->publicationAuthors()
            ->whereNull('orcid_sync')
            ->whereHas('author.user')
            ->with('author.user', 'publication.journal')
            ->get()
            ->filter(fn (PublicationAuthor $publicationAuthor): bool => $publicationAuthor->isEligibleForOrcid())
            ->each(function (PublicationAuthor $publicationAuthor) use ($publisher): void {
                if ($publicationAuthor->author->orcid_auto_sync_works) {
                    $publicationAuthor->recordOrcidIntent(OrcidSyncIntent::WANTED, $publisher, byAutoSync: true);
                    dispatch(new SyncWorkWithOrcid($publicationAuthor));
                } else {
                    Mail::queue(new WorkPublishedMail($publicationAuthor));
                }
            });
    }

    /**
     * Default Resource with eager loaded relationships
     */
    private function defaultResource(Publication $publication): JsonResource
    {
        $relationship = collect([
            'journal',
            'user',
            'manuscriptRecord',
            'region',
        ]);

        $publication->load($relationship->all());

        // Load publicationAuthors with chaperone to prevent circular reference
        $publication->load([
            'publicationAuthors' => fn ($q) => $q->with('author', 'organization')->chaperone('publication'),
        ]);

        return new PublicationResource($publication);
    }
}
