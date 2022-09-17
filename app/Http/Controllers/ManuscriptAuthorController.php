<?php

namespace App\Http\Controllers;

use App\Http\Resources\ManuscriptAuthorResource;
use App\Models\Author;
use App\Models\ManuscriptAuthor;
use App\Models\ManuscriptRecord;
use Illuminate\Http\Request;

class ManuscriptAuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ManuscriptRecord $manuscriptRecord)
    {
        $manuscriptAuthors = $manuscriptRecord->manuscriptAuthors()->with('author', 'organization')->get();

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
        $validated = $request->validate([
            'author_id' => 'required|integer|exists:authors,id|unique:manuscript_authors,author_id',
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
        $validated = $request->validate([
            'author_id' => 'required|integer|exists:authors,id',
            'is_corresponding_author' => 'boolean',
        ]);

        $author = Author::find($validated['author_id']);

        $manuscriptAuthor->manuscript_record_id = $manuscriptRecord->id;
        $manuscriptAuthor->author_id = $author->id;
        $manuscriptAuthor->organization_id = $author->organization_id;
        $manuscriptAuthor->save();

        return $manuscriptAuthor;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ManuscriptAuthor  $manuscriptAuthor
     * @return \Illuminate\Http\Response
     */
    public function destroy(ManuscriptRecord $manuscriptRecord, ManuscriptAuthor $manuscriptAuthor)
    {
        $manuscriptAuthor->delete();
        // response 204
        return response()->noContent();
    }
}
