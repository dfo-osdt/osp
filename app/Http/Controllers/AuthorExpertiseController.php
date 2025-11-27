<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExpertiseResource;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorExpertiseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Author $author)
    {
        return ExpertiseResource::collection($author->expertises);
    }

    /**
     * Sync the expertise given
     */
    public function store(Author $author, Request $request)
    {

        // can this user edit this author?
        $this->authorize('update', $author);

        $validated = $request->validate([
            'expertises' => ['present', 'array'],
            'expertises.*' => ['required', 'ulid', 'exists:expertises,id'],
        ]);

        $author->expertises()->sync($validated['expertises']);

        return ExpertiseResource::collection($author->expertises()->orderBy('name_en')->get());
    }
}
