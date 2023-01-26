<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthorResource;
use App\Models\Author;
use App\Queries\AuthorListQuery;
use App\Rules\Ocrid;
use App\Traits\PaginationLimitTrait;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AuthorController extends Controller
{
    use PaginationLimitTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $this->getLimitFromRequest($request);
        $authorListQuery = new AuthorListQuery($request);

        return AuthorResource::collection($authorListQuery->paginate($limit)->appends($request->query()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:authors,email',
            'organization_id' => 'required|exists:organizations,id',
            'orcid' => ['nullable', 'string', new Ocrid, 'unique:authors,orcid'],
        ]);

        $author = Author::create($validated);

        return new AuthorResource($author);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function show(Author $author)
    {
        $author->load('organization');

        return new AuthorResource($author);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Author $author)
    {
        Gate::authorize('update', $author);

        $validated = $request->validate([
            'first_name' => 'string',
            'last_name' => 'string',
            'email' => 'email|unique:authors,email,'.$author->id,
            'organization_id' => 'exists:organizations,id',
            'orcid' => ['nullable', 'string', new Ocrid, Rule::unique('authors', 'orcid')->ignore($author->id)],
        ]);

        // does this user have a user_id? If so, the name and email are controller
        // via the user model. We don't want to update those here.
        if ($author->user_id) {
            unset($validated['first_name']);
            unset($validated['last_name']);
            unset($validated['email']);
        }

        // does this user have a verified orcid? If so, we don't want to update it here.
        if ($author->orcid_verified) {
            unset($validated['orcid']);
        }

        $author->update($validated);
        $author->refresh();
        $author->load('organization');

        return new AuthorResource($author);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function destroy(Author $author)
    {
        //
    }
}
