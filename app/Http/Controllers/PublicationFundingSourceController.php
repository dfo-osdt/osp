<?php

namespace App\Http\Controllers;

use App\Http\Resources\FundingSourceResource;
use App\Models\FundingSource;
use App\Models\Publication;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class PublicationFundingSourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Publication $publication): ResourceCollection
    {
        Gate::authorize('view', $publication);

        $fundingSources = $publication->fundingSources;

        return FundingSourceResource::collection($fundingSources->load(['funder', 'fundable']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Publication $publication, Request $request): JsonResource
    {
        Gate::authorize('update', $publication);

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

        $publication->fundingSources()->save($fundingSource);

        return new FundingSourceResource($fundingSource->load(['funder', 'fundable']));
    }

    /**
     * Display the specified resource.
     */
    public function show(Publication $publication, FundingSource $fundingSource): JsonResource
    {
        Gate::authorize('view', $publication);

        return new FundingSourceResource($fundingSource->load(['funder', 'fundable']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Publication $publication, Request $request, FundingSource $fundingSource): JsonResource
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
     * @return Illuminate\Http\Resources\Json\JsonResource
     */
    public function destroy(Publication $publication, FundingSource $fundingSource): Response
    {
        Gate::authorize('update', $publication);

        $fundingSource->delete();

        return response()->noContent();
    }
}
