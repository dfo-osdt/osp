<?php

namespace App\Http\Controllers;

use App\Enums\Permissions\UserPermission;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use App\Models\ManuscriptAuthor;
use App\Models\Organization;
use App\Models\PublicationAuthor;
use App\Queries\AuthorListQuery;
use App\Rules\Ocrid;
use App\Rules\ValidListItems;
use App\Traits\PaginationLimitTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class AuthorController extends Controller
{
    use PaginationLimitTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResource
    {
        $limit = $this->getLimitFromRequest($request);
        $authorListQuery = new AuthorListQuery;

        return AuthorResource::collection($authorListQuery->paginate($limit)->appends($request->query()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResource
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:authors,email'],
            'is_independent' => ['boolean'],
            'organization_id' => ['required_unless:is_independent,true', 'exists:organizations,id'],
            'orcid' => ['nullable', 'string', new Ocrid, 'unique:authors,orcid'],
            'sector_id' => ['nullable', 'integer', 'exists:sectors,id'],
            'region_id' => ['nullable', 'integer', 'exists:regions,id'],
            'is_emeritus' => ['boolean'],
        ]);

        $validated = $this->resolveIndependentOrganization($validated);

        // sector, region and emeritus only apply to DFO authors
        if ((int) $validated['organization_id'] !== Organization::getDefaultOrganization()->id) {
            unset($validated['sector_id'], $validated['region_id'], $validated['is_emeritus']);
        }

        $author = (new Author)->create($validated);

        return new AuthorResource($author);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Author $author): JsonResource
    {

        $validated = $request->validate([
            'include' => [
                'bail',
                'string',
                new ValidListItems(['publications']),
            ],
        ]);

        $includes = collect(
            [
                'organization',
                'sector',
                'region',
                'expertises' => function ($query): void {
                    $query->orderBy('name_en');
                },
            ]
        );

        // If publications are requested, eager load nested relationships needed by policies
        if (isset($validated['include']) && str_contains($validated['include'], 'publications')) {
            $includes->put('publications', function ($query): void {
                $query->with([
                    'publicationAuthors' => fn ($q) => $q->with('author')->chaperone('publication'),
                    'region',
                ]);
            });
        }

        $author->load($includes->all());

        return new AuthorResource($author);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Author $author): JsonResource
    {
        Gate::authorize('update', $author);

        $willBeInternal = ! $request->boolean('is_independent')
            && (int) $request->input('organization_id', $author->organization_id) === Organization::getDefaultOrganization()->id;

        $validated = $request->validate([
            'first_name' => ['string'],
            'last_name' => ['string'],
            'email' => 'email|unique:authors,email,'.$author->id,
            'is_independent' => ['boolean'],
            'organization_id' => ['exists:organizations,id'],
            'orcid' => [
                Rule::excludeIf(fn () => $author->orcid_verified),
                'nullable',
                'string',
                new Ocrid,
                Rule::unique('authors', 'orcid')->ignore($author->id),
            ],
            'sector_id' => [
                Rule::requiredIf(fn (): bool => $this->requiresProfileField($request, $author, 'sector_id', $willBeInternal)),
                'nullable',
                'integer',
                'exists:sectors,id',
            ],
            'region_id' => [
                Rule::requiredIf(fn (): bool => $this->requiresProfileField($request, $author, 'region_id', $willBeInternal)),
                'nullable',
                'integer',
                'exists:regions,id',
            ],
            'is_emeritus' => ['boolean'],
            'sync_all_pivots' => ['boolean'],
        ]);

        // sector, region and emeritus only apply to DFO authors
        if (! $willBeInternal) {
            $validated = [...$validated, 'sector_id' => null, 'region_id' => null, 'is_emeritus' => false];
        }

        $syncAllPivots = $validated['sync_all_pivots'] ?? false;
        unset($validated['sync_all_pivots']);

        $validated = $this->resolveIndependentOrganization($validated);

        // does this user have a user_id? If so, the name and email are controller
        // via the user model. We don't want to update those here.
        if ($author->user_id) {
            unset($validated['first_name']);
            unset($validated['last_name']);
            unset($validated['email']);
        }

        $firstFilled = array_filter(
            ['sector_id', 'region_id'],
            fn (string $attribute): bool => $author->{$attribute} === null && ($validated[$attribute] ?? null) !== null,
        );

        $author->update($validated);

        $this->backfillAuthorships($author, $firstFilled);

        // If sync_all_pivots is true and organization_id was updated,
        // update all manuscript_authors and publication_authors
        if ($syncAllPivots && isset($validated['organization_id'])) {
            Gate::authorize(UserPermission::SYNCHRONIZE_AUTHOR_AFFILIATIONS);

            foreach ([$author->manuscriptAuthors(), $author->publicationAuthors()] as $authorships) {
                $this->syncAuthorshipAffiliation($authorships, $author, $validated['organization_id'], $willBeInternal);
            }

            activity()
                ->performedOn($author)
                ->withProperties([
                    'organization_id' => $validated['organization_id'],
                    'synced_pivots' => true,
                ])
                ->log('Author affiliation updated and synced to all manuscripts and publications');
        }

        $author->refresh();
        $author->load(['organization', 'sector', 'region']);

        return new AuthorResource($author);
    }

    /**
     * When a DFO author fills their sector or region for the first
     * time, copy it onto their DFO authorship rows that are still empty.
     * Rows that already hold a value are history and never change.
     *
     * @param  array<int, 'sector_id'|'region_id'>  $attributes
     */
    private function backfillAuthorships(Author $author, array $attributes): void
    {
        if ($attributes === []) {
            return;
        }

        $defaultOrganizationId = Organization::getDefaultOrganization()->id;
        $backfilled = [];

        foreach ($attributes as $attribute) {
            foreach ([$author->manuscriptAuthors(), $author->publicationAuthors()] as $authorships) {
                $authorships
                    ->where('organization_id', $defaultOrganizationId)
                    ->whereNull($attribute)
                    ->update([$attribute => $author->{$attribute}]);
            }

            $backfilled[$attribute] = $author->{$attribute};
        }

        activity()
            ->performedOn($author)
            ->withProperties($backfilled)
            ->log('Author profile first filled and backfilled to existing manuscripts and publications');
    }

    /**
     * Move every authorship row to the new affiliation. Rows leaving DFO
     * lose their sector, region and emeritus status; DFO rows only
     * gain the author's values where they are still empty.
     *
     * @param  HasMany<ManuscriptAuthor, Author>|HasMany<PublicationAuthor, Author>  $authorships
     */
    private function syncAuthorshipAffiliation(HasMany $authorships, Author $author, int $organizationId, bool $willBeInternal): void
    {
        if (! $willBeInternal) {
            $authorships->update([
                'organization_id' => $organizationId,
                'sector_id' => null,
                'region_id' => null,
                'is_emeritus' => null,
            ]);

            return;
        }

        $authorships->update(['organization_id' => $organizationId]);

        foreach (['sector_id', 'region_id', 'is_emeritus'] as $attribute) {
            if ($author->{$attribute} === null) {
                continue;
            }

            $authorships->clone()->whereNull($attribute)->update([$attribute => $author->{$attribute}]);
        }
    }

    /**
     * DFO authors must fill their sector and region when they edit
     * their own profile. Others editing the record (editors, or users
     * editing an author without an account) may leave them empty.
     */
    private function requiresProfileField(Request $request, Author $author, string $attribute, bool $willBeInternal): bool
    {
        $isOwnRecord = $author->user_id !== null && $author->user_id === $request->user()->id;

        if (! $isOwnRecord || ! $willBeInternal) {
            return false;
        }

        return $request->has($attribute) || $author->{$attribute} === null;
    }

    /**
     * When the author is flagged as an independent researcher, replace
     * the affiliation with the independent researcher organization.
     *
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function resolveIndependentOrganization(array $validated): array
    {
        if ($validated['is_independent'] ?? false) {
            $validated['organization_id'] = Organization::getIndependentOrganization()->id;
        }
        unset($validated['is_independent']);

        return $validated;
    }
}
