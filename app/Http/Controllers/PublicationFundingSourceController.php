<?php

namespace App\Http\Controllers;

use App\Http\Resources\FundingSourceResource;
use App\Models\FundingSource;
use App\Models\Publication;
use Gate;
use Illuminate\Http\Request;

class PublicationFundingSourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Publication $publication)
    {
        Gate::authorize('view', $publication);

        $fundingSources = $publication->fundingSources;

        return FundingSourceResource::collection($fundingSources->load(['funder', 'fundable']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Publication $publication, Request $request)
    {
        Gate::authorize('update', $publication);

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

        $publication->fundingSources()->save($fundingSource);

        return new FundingSourceResource($fundingSource->load(['funder', 'fundable']));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FundingSource  $fundingSource
     * @return \Illuminate\Http\Response
     */
    public function show(Publication $publication, FundingSource $fundingSource)
    {
        Gate::authorize('view', $publication);

        return new FundingSourceResource($fundingSource->load(['funder', 'fundable']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FundingSource  $fundingSource
     * @return \Illuminate\Http\Response
     */
    public function update(Publication $publication, Request $request, FundingSource $fundingSource)
    {
        Gate::authorize('update', $publication);

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
    public function destroy(Publication $publication, FundingSource $fundingSource)
    {
        Gate::authorize('update', $publication);

        $fundingSource->delete();

        return response()->noContent();
    }
}
