<?php

namespace App\Http\Controllers;

use App\Enums\ManuscriptRecordType;
use App\Http\Resources\ManuscriptPeerReviewerResource;
use App\Models\ManuscriptPeerReviewer;
use App\Models\ManuscriptRecord;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class ManuscriptPeerReviewerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ManuscriptRecord $manuscriptRecord): JsonResource
    {
        Gate::authorize('view', $manuscriptRecord);

        return ManuscriptPeerReviewerResource::collection($manuscriptRecord->peerReviewers()->with(['author.organization', 'manuscriptRecord'])->get());

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ManuscriptRecord $manuscriptRecord, Request $request): JsonResource
    {
        Gate::authorize('update', $manuscriptRecord);

        if ($manuscriptRecord->type === ManuscriptRecordType::PRIMARY) {
            throw ValidationException::withMessages(['manuscript_record_id' => 'A third-party primary manuscript cannot have peer reviewers']);
        }

        $validated = $request->validate([
            'author_id' => 'required|exists:authors,id',
        ]);

        if($manuscriptRecord->peerReviewers()->where('author_id', $validated['author_id'])->exists()) {
            throw ValidationException::withMessages(['author_id' => 'This author is already a peer reviewer for this manuscript']);
        }

        if($manuscriptRecord->manuscriptAuthors()->where('author_id', $validated['author_id'])->exists()) {
            throw ValidationException::withMessages(['author_id' => 'This author is already on the list of manuscript authors']);
        }

        $manuscriptPeerReviewer = new ManuscriptPeerReviewer($validated);
        $manuscriptPeerReviewer->manuscript_record_id = $manuscriptRecord->id;
        $manuscriptPeerReviewer->review_date = now();
        $manuscriptPeerReviewer->save();

        return new ManuscriptPeerReviewerResource($manuscriptPeerReviewer);

    }

    /**
     * Display the specified resource.
     */
    public function show(ManuscriptRecord $manuscriptRecord, ManuscriptPeerReviewer $manuscriptPeerReviewer)
    {
        Gate::authorize('view', $manuscriptPeerReviewer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ManuscriptRecord $manuscriptRecord, ManuscriptPeerReviewer $manuscriptPeerReviewer)
    {
        Gate::authorize('delete', $manuscriptPeerReviewer);

        $manuscriptPeerReviewer->delete();

        return response()->noContent();
    }
}
