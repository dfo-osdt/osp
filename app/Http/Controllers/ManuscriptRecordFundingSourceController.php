<?php

namespace App\Http\Controllers;

use App\Http\Resources\FundingSourceResource;
use App\Models\FundingSource;
use App\Models\ManuscriptRecord;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class ManuscriptRecordFundingSourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ManuscriptRecord $manuscriptRecord): ResourceCollection
    {
        Gate::authorize('view', $manuscriptRecord);

        $fundingSources = $manuscriptRecord->fundingSources;

        return FundingSourceResource::collection($fundingSources->load(['funder', 'fundable']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ManuscriptRecord $manuscriptRecord, Request $request): JsonResource
    {
        Gate::authorize('update', $manuscriptRecord);

        $validated = $request->validate([
            'funder_id' => 'required|exists:funders,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1500',
        ]);

        $fundingSource = new FundingSource([
            'funder_id' => $validated['funder_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
        ]);

        $manuscriptRecord->fundingSources()->save($fundingSource);

        return new FundingSourceResource($fundingSource->load(['funder', 'fundable']));
    }

    /**
     * Display the specified resource.
     */
    public function show(ManuscriptRecord $manuscriptRecord, FundingSource $fundingSource): JsonResource
    {
        Gate::authorize('view', $manuscriptRecord);

        return new FundingSourceResource($fundingSource->load(['funder', 'fundable']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ManuscriptRecord $manuscriptRecord, FundingSource $fundingSource, Request $request): JsonResource
    {
        Gate::authorize('update', $manuscriptRecord);

        $validated = $request->validate([
            'funder_id' => 'required|exists:funders,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1500',
        ]);

        $fundingSource->update([
            'funder_id' => $validated['funder_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
        ]);

        return new FundingSourceResource($fundingSource->load(['funder', 'fundable']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ManuscriptRecord $manuscriptRecord, FundingSource $fundingSource): Response
    {
        Gate::authorize('update', $manuscriptRecord);

        $fundingSource->delete();

        return response()->noContent();
    }
}
