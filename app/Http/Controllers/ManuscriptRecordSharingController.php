<?php

namespace App\Http\Controllers;

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
    public function store(Request $request, ManuscriptRecord $manuscriptRecord)
    {
        $this->authorize('share', $manuscriptRecord);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
