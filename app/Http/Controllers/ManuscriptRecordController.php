<?php

namespace App\Http\Controllers;

use App\Actions\CreatePublicationFromManuscript;
use App\Actions\DeleteManuscriptRecord;
use App\Enums\ManuscriptRecordStatus;
use App\Enums\ManuscriptRecordType;
use App\Events\ManuscriptRecordToReviewEvent;
use App\Events\ManuscriptRecordWithdrawnByAuthor;
use App\Events\PlanningBinder\FlaggedManuscriptAcceptedInJournal;
use App\Events\PlanningBinder\FlaggedManuscriptSubmittedToPrepint;
use App\Http\Resources\ManuscriptRecordMetadataResource;
use App\Http\Resources\ManuscriptRecordResource;
use App\Mail\ManuscriptRecordSubmittedToDFO;
use App\Models\Journal;
use App\Models\ManagementReviewStep;
use App\Models\ManuscriptRecord;
use App\Models\User;
use App\Rules\UserNotAManuscriptAuthor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class ManuscriptRecordController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResource
    {
        Gate::authorize('create', ManuscriptRecord::class);

        // validate that we have a type, title, and region_id
        $validated = $request->validate([
            'title' => 'required|max:255',
            'region_id' => 'required|exists:regions,id',
            'type' => ['required', new Enum(ManuscriptRecordType::class)],
        ]);

        $manuscriptRecord = new ManuscriptRecord($validated);
        $manuscriptRecord->status = ManuscriptRecordStatus::DRAFT;
        $manuscriptRecord->user_id = Auth::id();
        $manuscriptRecord->save();
        $manuscriptRecord->refresh();

        return $this->defaultResource($manuscriptRecord);
    }

    /**
     * Display the specified resource.
     */
    public function show(ManuscriptRecord $manuscriptRecord): JsonResource
    {
        Gate::authorize('view', $manuscriptRecord);

        return $this->defaultResource($manuscriptRecord);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ManuscriptRecord $manuscriptRecord): JsonResource
    {
        Gate::authorize('update', $manuscriptRecord);

        $validated = $request->validate([
            'title' => 'string|max:255',
            'region_id' => 'numeric|exists:regions,id',
            'functional_area_id' => 'nullable|numeric|exists:functional_areas,id',
            'type' => [new Enum(ManuscriptRecordType::class)],
            'abstract' => 'nullable|string',
            'pls_en' => 'nullable|string',
            'pls_fr' => 'nullable|string',
            'pls_source_language' => 'string|in:en,fr',
            'pls_approved_by_author' => 'boolean',
            'relevant_to' => 'nullable|string',
            'public_interest_information' => 'nullable|string',
            'potential_public_interest' => 'boolean',
            'apply_ogl' => 'boolean',
            'no_ogl_explanation' => 'nullable|string',
            'intends_open_access' => 'boolean',
            'open_access_rationale' => 'nullable|string',
        ]);

        $manuscriptRecord->update($validated);

        return $this->defaultResource($manuscriptRecord);
    }

    /** Delete a manuscript */
    public function destroy(Request $request, ManuscriptRecord $manuscriptRecord)
    {
        Gate::authorize('delete', $manuscriptRecord);

        DeleteManuscriptRecord::handle($manuscriptRecord);

        return response()->noContent();
    }

    /** Submit the manuscript record for review */
    public function submitForReview(Request $request, ManuscriptRecord $manuscriptRecord): JsonResource
    {
        Gate::authorize('submitForReview', $manuscriptRecord);

        $validated = $request->validate([
            'reviewer_user_id' => [
                new UserNotAManuscriptAuthor($manuscriptRecord),
                'required',
                'exists:users,id',
            ],
        ]);

        // validate that the record has all the required fields
        $manuscriptRecord->validateIsFilled();

        // get review user
        $reviewUser = User::findOrFail($validated['reviewer_user_id']);

        // create the first management review step for this record
        $reviewStep = new ManagementReviewStep;
        $reviewStep->manuscript_record_id = $manuscriptRecord->id;

        // is there an expected decision date for this manuscript type? Only primary for now.
        $decisionExpected = $manuscriptRecord->type === ManuscriptRecordType::PRIMARY;
        $reviewStep->decision_expected_by = $decisionExpected ? now()->addBusinessDays(config('osp.management_review.decision_expected_business_days')) : null;
        $reviewStep->user_id = $reviewUser->id;
        $reviewStep->save();

        // trigger event that the record was submitted
        ManuscriptRecordToReviewEvent::dispatch($manuscriptRecord, $reviewUser);

        $manuscriptRecord->status = ManuscriptRecordStatus::IN_REVIEW;
        $manuscriptRecord->sent_for_review_at = now();
        $manuscriptRecord->lockManuscriptFiles();
        $manuscriptRecord->save();

        return $this->defaultResource($manuscriptRecord);
    }

    /** Withdraw this manuscript - it will not be published */
    public function withdraw(ManuscriptRecord $manuscriptRecord): JsonResource
    {
        Gate::authorize('withdraw', $manuscriptRecord);

        $manuscriptRecord->status = ManuscriptRecordStatus::WITHDRAWN;
        $manuscriptRecord->withdrawn_on = now();
        $manuscriptRecord->save();

        ManuscriptRecordWithdrawnByAuthor::dispatch($manuscriptRecord);

        return $this->defaultResource($manuscriptRecord);
    }

    /** Mark the manuscript as submitted */
    public function submitted(Request $request, ManuscriptRecord $manuscriptRecord): JsonResource
    {
        Gate::authorize('markSubmitted', $manuscriptRecord);

        // validate the request has the submitted on date
        $validated = $request->validate([
            'submitted_to_journal_on' => 'required|date',
        ]);

        $manuscriptRecord->status = ManuscriptRecordStatus::SUBMITTED;
        $manuscriptRecord->submitted_to_journal_on = $validated['submitted_to_journal_on'];
        $manuscriptRecord->save();

        return $this->defaultResource($manuscriptRecord);
    }

    /** Mark the manuscript as accepted */
    public function accepted(Request $request, ManuscriptRecord $manuscriptRecord): JsonResource
    {
        Gate::authorize('markAccepted', $manuscriptRecord);

        // validate the request has the submitted on date
        $validated = $request->validate([
            'submitted_to_journal_on' => ['date', 'before_or_equal:accepted_on', Rule::requiredIf($manuscriptRecord->submitted_to_journal_on == null)],
            'accepted_on' => 'required|date|after_or_equal:submitted_to_journal_on',
            'journal_id' => ['required', 'exists:journals,id'],
        ]);

        // Ensure the journal selected matches the manuscript record type.
        $journal = Journal::find($validated['journal_id']);
        if ($manuscriptRecord->type === ManuscriptRecordType::SECONDARY && ! $journal->isDfoSeries()) {
            abort(422, 'Secondary MRFs must be published in a DFO series journal.');
        } elseif ($manuscriptRecord->type === ManuscriptRecordType::PRIMARY && $journal->isDfoSeries()) {
            abort(422, 'Primary MRFs cannot be published in a DFO series journal.');
        }

        $manuscriptRecord->status = ManuscriptRecordStatus::ACCEPTED;
        // if the submitted to journal date is given, set it.
        if ($validated['submitted_to_journal_on']) {
            $manuscriptRecord->submitted_to_journal_on = $validated['submitted_to_journal_on'];
        }
        $manuscriptRecord->accepted_on = $validated['accepted_on'];
        $manuscriptRecord->save();

        CreatePublicationFromManuscript::handle($manuscriptRecord, $journal);

        // if the manuscript is a secondary, send an email to the submissions team
        if ($manuscriptRecord->type === ManuscriptRecordType::SECONDARY) {
            Mail::queue(new ManuscriptRecordSubmittedToDFO($manuscriptRecord));
        }

        // has this manuscript been flaged for the planning binder?
        $planningBinderItem = $manuscriptRecord->planningBinderItem;
        if ($planningBinderItem) {
            FlaggedManuscriptAcceptedInJournal::commit(
                planning_binder_item_id: $planningBinderItem->id->id(),
                user_id: Auth::user()->id
            );
        }

        return $this->defaultResource($manuscriptRecord);
    }

    /** Submit to a preprint server / only works with preprint manuscripts */
    public function submittedToPreprint(Request $request, ManuscriptRecord $manuscriptRecord): JsonResource
    {
        Gate::authorize('submitToPreprint', $manuscriptRecord);

        // validate the request has the preprint url
        $validated = $request->validate([
            'accepted_on' => 'required|date',
            'preprint_url' => 'required|url',
        ]);

        $manuscriptRecord->submitted_to_journal_on = $validated['accepted_on'];
        $manuscriptRecord->accepted_on = $validated['accepted_on'];
        $manuscriptRecord->status = ManuscriptRecordStatus::ACCEPTED;

        $manuscriptRecord->preprint_url = $validated['preprint_url'];
        $manuscriptRecord->save();

        // has this manuscript been flaged for the planning binder?
        $planningBinderItem = $manuscriptRecord->planningBinderItem;
        if ($planningBinderItem) {
            FlaggedManuscriptSubmittedToPrepint::commit(
                planning_binder_item_id: $planningBinderItem->id->id(),
                user_id: Auth::user()->id
            );
        }

        return $this->defaultResource($manuscriptRecord);
    }

    /** Returns basic metadata on MRF all users area allowed to see */
    public function metadata(ManuscriptRecord $manuscriptRecord): JsonResource
    {
        return new ManuscriptRecordMetadataResource($this->loadPolicyRelationships($manuscriptRecord));
    }

    /** Default Resource with eager loaded properties */
    private function defaultResource(ManuscriptRecord $manuscriptRecord): JsonResource
    {
        return new ManuscriptRecordResource($this->loadPolicyRelationships($manuscriptRecord));
    }

    /**
     * Loads the relationships required for the policy checks in order to
     * avoid N+1 queries.
     */
    private function loadPolicyRelationships(ManuscriptRecord $manuscriptRecord): ManuscriptRecord
    {
        $relationships = collect(['user', 'shareables']);
        if ($manuscriptRecord->status === ManuscriptRecordStatus::ACCEPTED) {
            $relationships->push('publication');
        }

        return $manuscriptRecord->load($relationships->toArray());
    }
}
