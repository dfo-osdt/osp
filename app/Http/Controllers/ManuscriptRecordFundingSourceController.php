<?php

namespace App\Http\Controllers;

use App\Http\Resources\FundingSourceResource;
use App\Models\FundingSource;
use App\Models\ManuscriptRecord;
use App\Traits\LoadsManuscriptRecordPolicyRelationships;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class ManuscriptRecordFundingSourceController extends Controller
{
    use LoadsManuscriptRecordPolicyRelationships;

    /**
     * Display a listing of the resource.
     */
    public function index(ManuscriptRecord $manuscriptRecord): ResourceCollection
    {
        $manuscriptRecord->load($this->getManuscriptRecordPolicyRelationships());
        Gate::authorize('view', $manuscriptRecord);

        $fundingSources = $manuscriptRecord->fundingSources;

        /** @phpstan-ignore-next-line */
        return FundingSourceResource::collection($fundingSources->load([
            'funder',
            'fundable' => function (MorphTo $morphTo): void {
                $morphTo->morphWith([
                    ManuscriptRecord::class => ['user', 'manuscriptAuthors.author', 'shareables', 'managementReviewSteps'],
                ]);
            },
        ]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ManuscriptRecord $manuscriptRecord, Request $request): JsonResource
    {
        $manuscriptRecord->load($this->getManuscriptRecordPolicyRelationships());
        Gate::authorize('update', $manuscriptRecord);

        $validated = $request->validate([
            'funder_id' => ['required', 'exists:funders,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1500'],
        ]);

        $fundingSource = new FundingSource([
            'funder_id' => $validated['funder_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
        ]);

        $manuscriptRecord->fundingSources()->save($fundingSource);

        $fundingSource->setRelation('fundable', $manuscriptRecord);

        return new FundingSourceResource($fundingSource->load([
            'funder',
            'fundable' => function (MorphTo $morphTo): void {
                $morphTo->morphWith([
                    ManuscriptRecord::class => ['user', 'manuscriptAuthors.author', 'shareables', 'managementReviewSteps'],
                ]);
            },
        ]));

    }

    /**
     * Display the specified resource.
     */
    public function show(ManuscriptRecord $manuscriptRecord, FundingSource $fundingSource): JsonResource
    {
        $manuscriptRecord->load($this->getManuscriptRecordPolicyRelationships());
        Gate::authorize('view', $manuscriptRecord);

        $fundingSource->setRelation('fundable', $manuscriptRecord);

        return new FundingSourceResource($fundingSource->load([
            'funder',
            'fundable' => function (MorphTo $morphTo): void {
                $morphTo->morphWith([
                    ManuscriptRecord::class => ['user', 'manuscriptAuthors.author', 'shareables', 'managementReviewSteps'],
                ]);
            },
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ManuscriptRecord $manuscriptRecord, FundingSource $fundingSource, Request $request): JsonResource
    {
        $manuscriptRecord->load($this->getManuscriptRecordPolicyRelationships());
        Gate::authorize('update', $manuscriptRecord);

        $validated = $request->validate([
            'funder_id' => ['required', 'exists:funders,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1500'],
        ]);

        $fundingSource->update([
            'funder_id' => $validated['funder_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
        ]);

        $fundingSource->setRelation('fundable', $manuscriptRecord);

        return new FundingSourceResource($fundingSource->load([
            'funder',
            'fundable' => function (MorphTo $morphTo): void {
                $morphTo->morphWith([
                    ManuscriptRecord::class => ['user', 'manuscriptAuthors.author', 'shareables', 'managementReviewSteps'],
                ]);
            },
        ]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ManuscriptRecord $manuscriptRecord, FundingSource $fundingSource): Response
    {
        $manuscriptRecord->load($this->getManuscriptRecordPolicyRelationships());
        Gate::authorize('update', $manuscriptRecord);

        $fundingSource->delete();

        return response()->noContent();
    }
}
