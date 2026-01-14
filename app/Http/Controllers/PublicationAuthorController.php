<?php

namespace App\Http\Controllers;

use App\Http\Resources\PublicationAuthorResource;
use App\Models\Publication;
use App\Models\PublicationAuthor;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class PublicationAuthorController extends Controller
{
    /**
     * Load relationships needed for the resource and policies
     */
    private function loadResourceRelationships(PublicationAuthor $publicationAuthor, Publication $publication): PublicationAuthor
    {
        // Ensure publication has user_id loaded for policy checks
        if (!$publication->relationLoaded('user')) {
            $publication->loadMissing('user');
        }
        
        $publicationAuthor->setRelation('publication', $publication);
        $publicationAuthor->load('author.organization', 'organization');
        
        return $publicationAuthor;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Publication $publication): ResourceCollection
    {
        $publication->load('publicationAuthors.author', 'region');
        Gate::authorize('view', $publication);
        $publicationAuthors = $publication->publicationAuthors()
            ->with('author.organization', 'organization')
            ->chaperone('publication')
            ->orderBy('id')
            ->get();

        return PublicationAuthorResource::collection($publicationAuthors);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Publication $publication): JsonResource
    {
        $publication->load('publicationAuthors.author', 'region');
        Gate::authorize('update', $publication);

        $validated = $request->validate([
            'author_id' => ['required', 'integer', 'exists:authors,id',
                Rule::unique('publication_authors')->where(fn ($query) => $query->where('publication_id', $publication->id)), ],
            'is_corresponding_author' => ['boolean'],
        ]);

        $author = \App\Models\Author::query()->find($validated['author_id']);

        $publicationAuthor = new PublicationAuthor;
        $publicationAuthor->publication_id = $publication->id;
        $publicationAuthor->author_id = $validated['author_id'];
        $publicationAuthor->is_corresponding_author = $validated['is_corresponding_author'] ?? false;
        $publicationAuthor->organization_id = $author->organization_id;
        $publicationAuthor->save();
        
        $this->loadResourceRelationships($publicationAuthor, $publication);

        return PublicationAuthorResource::make($publicationAuthor);
    }

    /**
     * Display the specified resource.
     */
    public function show(Publication $publication, PublicationAuthor $publicationAuthor): JsonResource
    {
        $publication->load('publicationAuthors.author', 'region');
        Gate::authorize('view', $publication);

        $this->loadResourceRelationships($publicationAuthor, $publication);

        return PublicationAuthorResource::make($publicationAuthor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Publication $publication, PublicationAuthor $publicationAuthor): JsonResource
    {
        $publication->load('publicationAuthors.author', 'region');
        $publicationAuthor->setRelation('publication', $publication);
        Gate::authorize('update', $publicationAuthor);

        $validated = $request->validate([
            // 'author_id' => 'integer|exists:authors,id',
            'is_corresponding_author' => ['boolean'],
            'organization_id' => ['integer', 'exists:organizations,id'],
        ]);

        if (isset($validated['organization_id'])) {
            $publicationAuthor->organization_id = $validated['organization_id'];
        }
        if (isset($validated['is_corresponding_author'])) {
            $publicationAuthor->is_corresponding_author = $validated['is_corresponding_author'];
        }
        $publicationAuthor->save();
        
        $publicationAuthor->refresh();
        $this->loadResourceRelationships($publicationAuthor, $publication);

        return PublicationAuthorResource::make($publicationAuthor);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publication $publication, PublicationAuthor $publicationAuthor): Response
    {
        $publication->load('publicationAuthors.author', 'region');
        $publicationAuthor->setRelation('publication', $publication);
        Gate::authorize('delete', $publicationAuthor);

        $publicationAuthor->delete();

        // response 204
        return response()->noContent();
    }
}
