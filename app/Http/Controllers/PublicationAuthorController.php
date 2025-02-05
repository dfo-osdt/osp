<?php

namespace App\Http\Controllers;

use App\Http\Resources\PublicationAuthorResource;
use App\Models\Author;
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
     * Display a listing of the resource.
     */
    public function index(Publication $publication): ResourceCollection
    {
        Gate::authorize('view', $publication);
        $publicationAuthors = $publication->publicationAuthors()->with('author', 'organization')->orderBy('id')->get();

        return PublicationAuthorResource::collection($publicationAuthors);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Publication $publication): JsonResource
    {
        Gate::authorize('update', $publication);

        $validated = $request->validate([
            'author_id' => ['required', 'integer', 'exists:authors,id',
                Rule::unique('publication_authors')->where(function ($query) use ($publication) {
                    return $query->where('publication_id', $publication->id);
                }), ],
            'is_corresponding_author' => 'boolean',
        ]);

        $author = Author::find($validated['author_id']);

        $publicationAuthor = new PublicationAuthor;
        $publicationAuthor->publication_id = $publication->id;
        $publicationAuthor->author_id = $validated['author_id'];
        $publicationAuthor->is_corresponding_author = $validated['is_corresponding_author'] ?? false;
        $publicationAuthor->organization_id = $author->organization_id;
        $publicationAuthor->save();
        $publicationAuthor->load('author', 'organization');

        return PublicationAuthorResource::make($publicationAuthor);
    }

    /**
     * Display the specified resource.
     */
    public function show(Publication $publication, PublicationAuthor $publicationAuthor): JsonResource
    {
        Gate::authorize('view', $publication);

        $publicationAuthor->load('author', 'organization');

        return PublicationAuthorResource::make($publicationAuthor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Publication $publication, PublicationAuthor $publicationAuthor): JsonResource
    {
        Gate::authorize('update', $publicationAuthor);

        $validated = $request->validate([
            // 'author_id' => 'integer|exists:authors,id',
            'is_corresponding_author' => 'boolean',
            'organization_id' => 'integer|exists:organizations,id',
        ]);

        if (isset($validated['organization_id'])) {
            $publicationAuthor->organization_id = $validated['organization_id'];
        }
        if (isset($validated['is_corresponding_author'])) {
            $publicationAuthor->is_corresponding_author = $validated['is_corresponding_author'];
        }
        $publicationAuthor->save();
        $publicationAuthor->refresh()->load('author', 'organization');

        return PublicationAuthorResource::make($publicationAuthor);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publication $publication, PublicationAuthor $publicationAuthor): Response
    {
        Gate::authorize('delete', $publicationAuthor);

        $publicationAuthor->delete();

        // response 204
        return response()->noContent();
    }
}
