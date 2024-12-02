<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthorEmploymentResource;
use App\Jobs\SyncAuthorEmploymentWithOrcid;
use App\Models\Author;
use App\Models\AuthorEmployment;
use App\Models\Organization;
use Illuminate\Http\Request;

class AuthorEmploymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Author $author)
    {
        //validate user can do this
        $this->authorize('viewAny', [AuthorEmployment::class, $author]);

        return AuthorEmploymentResource::collection($author->employments()->orderByDesc('start_date')->with('organization')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Author $author, Request $request)
    {
        $this->authorize('create', [AuthorEmployment::class, $author]);

        $defaultOrganization = Organization::getDefaultOrganization();

        $validated = $request->validate([
            'organization_id' => 'nullable|exists:organizations,id',
            'role_title' => 'nullable|string|max:255',
            'department_name' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
        ]);

        // if orgnaiztion isn't the default, it is forbidden
        if ($validated['organization_id'] && $validated['organization_id'] !== $defaultOrganization->id) {
            abort(403, 'You are not allowed to create employments for other organizations');
        }

        $authorEmployment = AuthorEmployment::create([
            'author_id' => $author->id,
            'organization_id' => $defaultOrganization->id,
            'role_title' => $validated['role_title'],
            'department_name' => $validated['department_name'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        $authorEmployment->load('organization');

        SyncAuthorEmploymentWithOrcid::dispatch($authorEmployment, SyncAuthorEmploymentWithOrcid::SYNC_TYPE_CREATE);

        return AuthorEmploymentResource::make($authorEmployment);

    }

    /**
     * Display the specified resource.
     */
    public function show(Author $author, AuthorEmployment $authorEmployment)
    {
        $this->authorize('view', $authorEmployment);

        $authorEmployment->load('organization');

        return AuthorEmploymentResource::make($authorEmployment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Author $author, Request $request, AuthorEmployment $authorEmployment)
    {
        $this->authorize('update', $authorEmployment);

        $validated = $request->validate([
            'role_title' => 'nullable|string|max:50',
            'department_name' => 'nullable|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
        ]);

        $authorEmployment->update($validated);
        $authorEmployment->orcid_updated_at = null;
        $authorEmployment->save();
        $authorEmployment->load('organization');
        $authorEmployment->refresh();

        SyncAuthorEmploymentWithOrcid::dispatch($authorEmployment, SyncAuthorEmploymentWithOrcid::SYNC_TYPE_UPDATE);

        return AuthorEmploymentResource::make($authorEmployment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author, AuthorEmployment $authorEmployment)
    {
        $this->authorize('delete', $authorEmployment);

        SyncAuthorEmploymentWithOrcid::dispatch($authorEmployment, SyncAuthorEmploymentWithOrcid::SYNC_TYPE_DELETE);

        $authorEmployment->delete();

        return response()->noContent();
    }
}
