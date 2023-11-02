<?php

namespace App\Http\Controllers;

use App\Events\ItemShared;
use App\Http\Requests\ShareablePostRequest;
use App\Http\Resources\ShareableResource;
use App\Models\ManuscriptRecord;
use App\Models\Shareable;
use Illuminate\Http\Request;

class ManuscriptRecordSharingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ManuscriptRecord $manuscriptRecord)
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
    public function store(ShareablePostRequest $request, ManuscriptRecord $manuscriptRecord)
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

        return new ShareableResource($shareable->load(['user', 'sharingUser']));
    }

    /**
     * Display the specified resource.
     */
    public function show(ManuscriptRecord $manuscriptRecord, Shareable $shareable)
    {
        $this->authorize('view', $manuscriptRecord);

        return new ShareableResource($shareable->load(['user', 'sharingUser']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ManuscriptRecord $manuscriptRecord, Shareable $shareable)
    {
        $this->authorize('share', $manuscriptRecord);

        $validated = $request->validate([
            'can_edit' => 'boolean',
            'can_delete' => 'boolean',
            'expires_at' => 'nullable|date',
        ]);

        $shareable->update($validated);

        return new ShareableResource($shareable->load(['user', 'sharingUser']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ManuscriptRecord $manuscriptRecord, Shareable $shareable)
    {
        $this->authorize('share', $manuscriptRecord);

        $shareable->delete();

        return response()->noContent();
    }
}
