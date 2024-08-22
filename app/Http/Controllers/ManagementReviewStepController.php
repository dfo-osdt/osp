<?php

namespace App\Http\Controllers;

use App\Enums\ManagementReviewStepDecision;
use App\Enums\ManagementReviewStepStatus;
use App\Enums\ManuscriptRecordStatus;
use App\Events\ManagementReviewStepCreated;
use App\Events\ManuscriptManagementReviewComplete;
use App\Events\ManuscriptRecordWithdrawnByAuthor;
use App\Events\ManuscriptRecordWithheldByManagement;
use App\Http\Resources\ManagementReviewStepResource;
use App\Models\ManagementReviewStep;
use App\Models\ManuscriptRecord;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\Rule;
use Validator;

class ManagementReviewStepController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ManuscriptRecord $manuscriptRecord): JsonResource
    {
        Gate::authorize('view', $manuscriptRecord);

        $managementReviewSteps = $manuscriptRecord->managementReviewSteps()->with('user')->orderBy('id')->get();

        return ManagementReviewStepResource::collection($managementReviewSteps);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ManuscriptRecord $manuscriptRecord, ManagementReviewStep $managementReviewStep): JsonResource
    {
        Gate::authorize('update', $managementReviewStep);

        $validated = $request->validate([
            'comments' => 'string|nullable',
        ]);

        $managementReviewStep->update($validated);

        return new ManagementReviewStepResource($managementReviewStep);
    }

    // actions
    public function approve(Request $request, ManuscriptRecord $manuscriptRecord, ManagementReviewStep $managementReviewStep): JsonResource
    {
        Gate::authorize('decide', $managementReviewStep);

        $validated = $request->validate([
            'next_user_id' => ['exists:users,id', Rule::notIn([$managementReviewStep->user_id])],
            'comments' => 'string|nullable',
        ]);

        if (isset($validated['comments'])) {
            $managementReviewStep->comments = $validated['comments'];
        }
        // if the next user is not set, then the review is complete and the manuscript review is complete.
        if (isset($validated['next_user_id'])) {
            // a comment is required to send to the next user.
            Validator::make($managementReviewStep->toArray(), [
                'comments' => 'required|string',
            ])->validate();

            $nextReviewStep = new ManagementReviewStep;
            $nextReviewStep->user_id = $validated['next_user_id'];
            $nextReviewStep->status = ManagementReviewStepStatus::PENDING;
            $nextReviewStep->decision = ManagementReviewStepDecision::NONE;
            $nextReviewStep->manuscript_record_id = $manuscriptRecord->id;
            $nextReviewStep->previous_step_id = $managementReviewStep->id;
            $nextReviewStep->decision_expected_by = $managementReviewStep->decision_expected_by;

            $nextReviewStep->saveOrFail();

            // send event that a management review step has been created.
            ManagementReviewStepCreated::dispatch($nextReviewStep);
        } else {
            // this is the final step of the review.
            $manuscriptRecord->status = ManuscriptRecordStatus::REVIEWED;
            $manuscriptRecord->reviewed_at = now();
            $manuscriptRecord->lockManuscriptFiles();
            $manuscriptRecord->saveOrFail();

            // send event that a manuscript record review is complete.
            ManuscriptManagementReviewComplete::dispatch($manuscriptRecord);
        }

        $managementReviewStep->status = ManagementReviewStepStatus::COMPLETED;
        $managementReviewStep->completed_at = now();
        $managementReviewStep->decision = ManagementReviewStepDecision::APPROVED;
        $managementReviewStep->saveOrFail();

        return new ManagementReviewStepResource($managementReviewStep);
    }

    public function withhold(Request $request, ManuscriptRecord $manuscriptRecord, ManagementReviewStep $managementReviewStep): JsonResource
    {
        Gate::authorize('decide', $managementReviewStep);

        $validated = $request->validate([
            'next_user_id' => ['exists:users,id', Rule::notIn([$managementReviewStep->user_id])],
            'comments' => 'string|nullable',
        ]);

        if (isset($validated['comments'])) {
            $managementReviewStep->comments = $validated['comments'];
        }

        // if the next user is not set, only a user with permission can complete the review.
        if (! isset($validated['next_user_id'])) {
            Gate::authorize('withhold_and_complete_management_review');
        }

        // a comment is always required if the manuscript is being withheld.
        Validator::make($managementReviewStep->toArray(), [
            'comments' => 'required|string',
        ])->validate();

        // if the next user is not set, then the review is complete and the manuscript review is complete.
        if (isset($validated['next_user_id'])) {
            $nextReviewStep = new ManagementReviewStep;
            $nextReviewStep->user_id = $validated['next_user_id'];
            $nextReviewStep->status = ManagementReviewStepStatus::PENDING;
            $nextReviewStep->decision = ManagementReviewStepDecision::NONE;
            $nextReviewStep->manuscript_record_id = $manuscriptRecord->id;
            $nextReviewStep->previous_step_id = $managementReviewStep->id;
            $nextReviewStep->decision_expected_by = $managementReviewStep->decision_expected_by;

            $nextReviewStep->saveOrFail();

            // send event that a management review step has been created.
            ManagementReviewStepCreated::dispatch($nextReviewStep);
        } else {
            // this is the final step of the review - the manuscript was withheld!
            $manuscriptRecord->status = ManuscriptRecordStatus::WITHHELD;
            $manuscriptRecord->reviewed_at = now();
            $manuscriptRecord->lockManuscriptFiles();
            $manuscriptRecord->saveOrFail();

            // send event that a manuscript record is withheld by management.
            ManuscriptRecordWithheldByManagement::dispatch($manuscriptRecord);
        }

        $managementReviewStep->status = ManagementReviewStepStatus::COMPLETED;
        $managementReviewStep->completed_at = now();
        $managementReviewStep->decision = ManagementReviewStepDecision::WITHHELD;
        $managementReviewStep->saveOrFail();

        return new ManagementReviewStepResource($managementReviewStep);
    }

    public function reassign(Request $request, ManuscriptRecord $manuscriptRecord, ManagementReviewStep $managementReviewStep): JsonResource
    {
        Gate::authorize('decide', $managementReviewStep);

        $validated = $request->validate([
            'next_user_id' => ['required', 'exists:users,id', Rule::notIn([$managementReviewStep->user_id])],
            'comments' => 'string|nullable',
        ]);

        if (isset($validated['comments'])) {
            $managementReviewStep->comments = $validated['comments'];
        }
        // validate that the review step has a comment
        Validator::make($managementReviewStep->toArray(), [
            'comments' => 'required|string',
        ])->validate();

        $nextReviewStep = new ManagementReviewStep;
        $nextReviewStep->user_id = $validated['next_user_id'];
        $nextReviewStep->status = ManagementReviewStepStatus::PENDING;
        $nextReviewStep->decision = ManagementReviewStepDecision::NONE;
        $nextReviewStep->manuscript_record_id = $manuscriptRecord->id;
        $nextReviewStep->previous_step_id = $managementReviewStep->id;
        $nextReviewStep->decision_expected_by = now()->addBusinessDays(config('osp.management_review.decision_expected_business_days'));
        $nextReviewStep->saveOrFail();

        // send event that a management review step has been created.
        ManagementReviewStepCreated::dispatch($nextReviewStep);

        $managementReviewStep->status = ManagementReviewStepStatus::REASSIGN;
        $managementReviewStep->completed_at = now();
        $managementReviewStep->saveOrFail();

        return new ManagementReviewStepResource($managementReviewStep);
    }

    public function flag(Request $request, ManuscriptRecord $manuscriptRecord, ManagementReviewStep $managementReviewStep): JsonResource
    {
        Gate::authorize('decide', $managementReviewStep);

        $validated = $request->validate([
            'comments' => 'string|nullable',
        ]);

        if (isset($validated['comments'])) {
            $managementReviewStep->comments = $validated['comments'];
        }
        // validate that the review step has a comment
        Validator::make($managementReviewStep->toArray(), [
            'comments' => 'required|string',
        ])->validate();

        $nextReviewStep = new ManagementReviewStep;
        $nextReviewStep->user_id = $manuscriptRecord->user_id;
        $nextReviewStep->status = ManagementReviewStepStatus::ON_HOLD;
        $nextReviewStep->decision = ManagementReviewStepDecision::NONE;
        $nextReviewStep->manuscript_record_id = $manuscriptRecord->id;
        $nextReviewStep->previous_step_id = $managementReviewStep->id;
        $nextReviewStep->saveOrFail();

        ManagementReviewStepCreated::dispatch($nextReviewStep);

        $managementReviewStep->status = ManagementReviewStepStatus::COMPLETED;
        $managementReviewStep->decision = ManagementReviewStepDecision::FLAGGED;
        $managementReviewStep->completed_at = now();
        $managementReviewStep->saveOrFail();

        return new ManagementReviewStepResource($managementReviewStep);
    }

    public function flaggedResponse(Request $request, ManuscriptRecord $manuscriptRecord, ManagementReviewStep $managementReviewStep): JsonResource
    {
        Gate::authorize('update', $managementReviewStep);

        $validated = $request->validate([
            'comments' => 'string|nullable',
        ]);

        // return to the manager that sent the manuscript back to the author.
        $previousStep = $managementReviewStep->previousStep;

        if (isset($validated['comments'])) {
            $managementReviewStep->comments = $validated['comments'];
        }
        // validate that the review step has a comment
        Validator::make($managementReviewStep->toArray(), [
            'comments' => 'required|string',
        ])->validate();

        $nextReviewStep = new ManagementReviewStep;
        $nextReviewStep->user_id = $previousStep->user_id;
        $nextReviewStep->status = ManagementReviewStepStatus::PENDING;
        $nextReviewStep->decision = ManagementReviewStepDecision::NONE;
        $nextReviewStep->manuscript_record_id = $manuscriptRecord->id;
        $nextReviewStep->previous_step_id = $managementReviewStep->id;
        $nextReviewStep->decision_expected_by = now()->addBusinessDays(config('osp.management_review.decision_expected_business_days'));
        $nextReviewStep->saveOrFail();

        ManagementReviewStepCreated::dispatch($nextReviewStep);

        $managementReviewStep->status = ManagementReviewStepStatus::COMPLETED;
        $managementReviewStep->decision = ManagementReviewStepDecision::NONE;
        $managementReviewStep->completed_at = now();
        $managementReviewStep->saveOrFail();

        $manuscriptRecord->lockManuscriptFiles();

        return new ManagementReviewStepResource($managementReviewStep);
    }

    public function withdraw(Request $request, ManuscriptRecord $manuscriptRecord, ManagementReviewStep $managementReviewStep): JsonResource
    {
        Gate::authorize('withdraw', $managementReviewStep);

        $validated = $request->validate([
            'comments' => 'string|nullable',
        ]);

        if (isset($validated['comments'])) {
            $managementReviewStep->comments = $validated['comments'];
        }

        // validate that the review step has a comment
        Validator::make($managementReviewStep->toArray(), [
            'comments' => 'required|string',
        ])->validate();

        $managementReviewStep->status = ManagementReviewStepStatus::COMPLETED;
        $managementReviewStep->decision = ManagementReviewStepDecision::WITHDRAWN;
        $managementReviewStep->completed_at = now();
        $managementReviewStep->saveOrFail();

        $manuscriptRecord->status = ManuscriptRecordStatus::WITHDRAWN;
        $manuscriptRecord->withdrawn_on = now();
        $manuscriptRecord->saveOrFail();

        ManuscriptRecordWithdrawnByAuthor::dispatch($manuscriptRecord);

        return new ManagementReviewStepResource($managementReviewStep);
    }
}
