<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreManagementReviewDelegationRequest;
use App\Http\Resources\ManagementReviewDelegationResource;
use App\Models\ManagementReviewDelegation;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ManagementReviewDelegationController extends Controller
{
    public function index(): JsonResource
    {
        $delegations = Auth::user()
            ->managementReviewDelegations()
            ->with('delegate')
            ->orderByDesc('created_at')
            ->get();

        return ManagementReviewDelegationResource::collection($delegations);
    }

    public function store(StoreManagementReviewDelegationRequest $request): JsonResource
    {
        $delegation = Auth::user()->managementReviewDelegations()->create($request->validated());
        $delegation->load('delegate');

        return new ManagementReviewDelegationResource($delegation);
    }

    public function destroy(ManagementReviewDelegation $managementReviewDelegation): JsonResource
    {
        if ($managementReviewDelegation->user_id !== Auth::id()) {
            abort(403);
        }

        $managementReviewDelegation->update(['ended_early_at' => now()]);
        $managementReviewDelegation->load('delegate');

        return new ManagementReviewDelegationResource($managementReviewDelegation);
    }
}
