<?php

namespace App\Http\Controllers;

use App\Events\ItemShared;
use App\Http\Requests\ShareablePostRequest;
use App\Http\Resources\ShareableResource;
use App\Models\ManuscriptRecord;
use App\Models\Shareable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ManuscriptRecordSharingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection<ShareableResource>
     */
    public function index(ManuscriptRecord $manuscriptRecord): AnonymousResourceCollection
    {
        $this->authorize('view', $manuscriptRecord);

        return ShareableResource::collection(Shareable::where([
            'shareable_type' => ManuscriptRecord::class,
            'shareable_id' => $manuscriptRecord->id,
        ])->with(['user', 'sharingUser'])->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ShareablePostRequest $request, ManuscriptRecord $manuscriptRecord): \App\Http\Resources\ShareableResource
    {
        $this->authorize('share', $manuscriptRecord);

        $validated = $request->validated();

        $shareable = Shareable::updateOrCreate([
            'shareable_type' => ManuscriptRecord::class,
            'shareable_id' => $manuscriptRecord->id,
            'user_id' => $validated['user_id'],
        ], [
            'shared_by' => $request->user()->id,
            'can_edit' => $validated['can_edit'],
            'can_delete' => $validated['can_delete'],
            'expires_at' => $validated['expires_at'],
        ]);

        ItemShared::dispatch($shareable);

        activity()
            ->performedOn($manuscriptRecord)
            ->withProperties($shareable->toArray())
            ->causedBy($request->user())
            ->log('manuscript.shared.created');

        return new ShareableResource($shareable->load(['user', 'sharingUser']));
    }

    /**
     * Display the specified resource.
     */
    public function show(ManuscriptRecord $manuscriptRecord, Shareable $shareable): \App\Http\Resources\ShareableResource
    {
        $this->authorize('view', $manuscriptRecord);

        return new ShareableResource($shareable->load(['user', 'sharingUser']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ManuscriptRecord $manuscriptRecord, Shareable $shareable): \App\Http\Resources\ShareableResource
    {
        $this->authorize('share', $manuscriptRecord);

        $validated = $request->validate([
            'can_edit' => 'boolean',
            'can_delete' => 'boolean',
            'expires_at' => 'nullable|date',
        ]);

        $shareable->update($validated);

        activity()
            ->performedOn($manuscriptRecord)
            ->withProperties($shareable->toArray())
            ->causedBy($request->user())
            ->log('manuscript.shared.updated');

        return new ShareableResource($shareable->load(['user', 'sharingUser']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, ManuscriptRecord $manuscriptRecord, Shareable $shareable)
    {
        $this->authorize('share', $manuscriptRecord);

        $shareable->delete();

        activity()
            ->performedOn($manuscriptRecord)
            ->withProperties($shareable->toArray())
            ->causedBy($request->user())
            ->log('manuscript.shared.removed');

        return response()->noContent();

    }
}
