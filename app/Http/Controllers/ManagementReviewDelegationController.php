<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreManagementReviewDelegationRequest;
use App\Http\Resources\ManagementReviewDelegationResource;
use App\Mail\DelegationCreatedMail;
use App\Models\ManagementReviewDelegation;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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
        $delegation->load(['delegate', 'user']);

        Mail::queue(new DelegationCreatedMail($delegation));

        return new ManagementReviewDelegationResource($delegation);
    }

    public function destroy(ManagementReviewDelegation $managementReviewDelegation): JsonResource
    {
        if ($managementReviewDelegation->user_id !== Auth::id()) {
            abort(403);
        }

        if ($managementReviewDelegation->isScheduled()) {
            $managementReviewDelegation->load('delegate');
            $resource = new ManagementReviewDelegationResource($managementReviewDelegation);
            $managementReviewDelegation->delete();

            return $resource;
        }

        $managementReviewDelegation->update(['ended_early_at' => now()]);
        $managementReviewDelegation->load('delegate');

        return new ManagementReviewDelegationResource($managementReviewDelegation);
    }
}
