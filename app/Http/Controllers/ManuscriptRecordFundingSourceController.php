<?php

namespace App\Http\Controllers;

use App\Http\Resources\FundingSourceResource;
use App\Models\FundingSource;
use App\Models\ManuscriptRecord;
use Gate;
use Illuminate\Http\Request;

class ManuscriptRecordFundingSourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ManuscriptRecord $manuscriptRecord)
    {
        Gate::authorize('view', $manuscriptRecord);

        $fundingSources = $manuscriptRecord->fundingSources;

        return FundingSourceResource::collection($fundingSources->load(['funder', 'fundable']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ManuscriptRecord $manuscriptRecord, Request $request)
    {
        Gate::authorize('update', $manuscriptRecord);

        $validated = $request->validate([
            'funder_id' => 'required|exists:funders,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1500',
        ]);

        $fundingSource = FundingSource::make([
            'funder_id' => $validated['funder_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
        ]);

        $manuscriptRecord->fundingSources()->save($fundingSource);

        return new FundingSourceResource($fundingSource->load(['funder', 'fundable']));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FundingSource  $fundingSource
     * @return \Illuminate\Http\Response
     */
    public function show(ManuscriptRecord $manuscriptRecord, FundingSource $fundingSource)
    {
        Gate::authorize('view', $manuscriptRecord);

        return new FundingSourceResource($fundingSource->load(['funder', 'fundable']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FundingSource  $fundingSource
     * @return \Illuminate\Http\Response
     */
    public function update(ManuscriptRecord $manuscriptRecord, Request $request, FundingSource $fundingSource)
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
     *
     * @param  \App\Models\FundingSource  $fundingSource
     * @return \Illuminate\Http\Response
     */
    public function destroy(ManuscriptRecord $manuscriptRecord, FundingSource $fundingSource)
    {
        Gate::authorize('update', $manuscriptRecord);

        $fundingSource->delete();

        return response()->noContent();
    }
}
