<?php

namespace App\Http\Controllers;

use App\Actions\DeletePublication;
use App\Enums\PublicationStatus;
use App\Http\Resources\PublicationResource;
use App\Models\Publication;
use App\Models\User;
use App\Queries\PublicationListQuery;
use App\Rules\Doi;
use App\Traits\PaginationLimitTrait;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;

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
            ]);

        $publicationListQuery = new PublicationListQuery($request, $baseQuery);

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
            'title' => ['required'],
            'journal_id' => ['required', 'exists:journals,id'],
            'doi' => ['nullable', 'string', new Doi],
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
                        if (! isset($request['accepted_on'])) {
                            $request['accepted_on'] = $publication->accepted_on;
                        }
                        break;
                    default:
                        // likely, only way we're here is someone is having a go at the API directly.
                        throw ValidationException::withMessages(['status' => 'Cannot change status back to accepted']);
                }
            }
        }

        // validate the rest of the request
        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string'],
            'journal_id' => ['sometimes', 'required', 'exists:journals,id'],
            'doi' => ['string', 'nullable', new Doi],
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
