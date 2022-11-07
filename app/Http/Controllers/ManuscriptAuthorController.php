<?php

namespace App\Http\Controllers;

use App\Http\Resources\ManuscriptAuthorResource;
use App\Models\Author;
use App\Models\ManuscriptAuthor;
use App\Models\ManuscriptRecord;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ManuscriptAuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ManuscriptRecord $manuscriptRecord)
    {
        Gate::authorize('view', $manuscriptRecord);
        $manuscriptAuthors = $manuscriptRecord->manuscriptAuthors()->with('author', 'organization')->orderBy('id')->get();

        return ManuscriptAuthorResource::collection($manuscriptAuthors);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ManuscriptRecord $manuscriptRecord)
    {
        Gate::authorize('update', $manuscriptRecord);

        $validated = $request->validate([
            'author_id' => ['required', 'integer', 'exists:authors,id',
                Rule::unique('manuscript_authors')->where(function ($query) use ($manuscriptRecord) {
                    return $query->where('manuscript_record_id', $manuscriptRecord->id);
                }), ],
            'is_corresponding_author' => 'boolean',
        ]);

        $author = Author::find($validated['author_id']);

        $manuscriptAuthor = new ManuscriptAuthor();
        $manuscriptAuthor->manuscript_record_id = $manuscriptRecord->id;
        $manuscriptAuthor->author_id = $validated['author_id'];
        $manuscriptAuthor->is_corresponding_author = $validated['is_corresponding_author'] ?? false;
        $manuscriptAuthor->organization_id = $author->organization_id;
        $manuscriptAuthor->save();
        $manuscriptAuthor->load('author', 'organization');

        return ManuscriptAuthorResource::make($manuscriptAuthor);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ManuscriptAuthor  $manuscriptAuthor
     * @return \Illuminate\Http\Response
     */
    public function show(ManuscriptRecord $manuscriptRecord, ManuscriptAuthor $manuscriptAuthor)
    {
        Gate::authorize('view', $manuscriptRecord);

        $manuscriptAuthor->load('author', 'organization');

        return ManuscriptAuthorResource::make($manuscriptAuthor);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ManuscriptAuthor  $manuscriptAuthor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ManuscriptRecord $manuscriptRecord, ManuscriptAuthor $manuscriptAuthor)
    {
        Gate::authorize('update', $manuscriptRecord);

        $validated = $request->validate([
            //'author_id' => 'integer|exists:authors,id',
            'is_corresponding_author' => 'boolean',
            'organization_id' => 'integer|exists:organizations,id',
        ]);

        if (isset($validated['organization_id'])) {
            $manuscriptAuthor->organization_id = $validated['organization_id'];
        }
        if (isset($validated['is_corresponding_author'])) {
            $manuscriptAuthor->is_corresponding_author = $validated['is_corresponding_author'];
        }
        $manuscriptAuthor->save();
        $manuscriptAuthor->refresh()->load('author', 'organization');

        return ManuscriptAuthorResource::make($manuscriptAuthor);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ManuscriptAuthor  $manuscriptAuthor
     * @return \Illuminate\Http\Response
     */
    public function destroy(ManuscriptRecord $manuscriptRecord, ManuscriptAuthor $manuscriptAuthor)
    {
        Gate::authorize('delete', $manuscriptAuthor);

        $manuscriptAuthor->delete();
        // response 204
        return response()->noContent();
    }
}
