<?php

namespace App\Http\Controllers;

use App\Actions\CreatePublicationFromManuscript;
use App\Actions\DeleteManuscriptRecord;
use App\Enums\ManuscriptRecordStatus;
use App\Enums\ManuscriptRecordType;
use App\Events\ManuscriptRecordAccepted;
use App\Events\ManuscriptRecordSubmitted;
use App\Events\ManuscriptRecordToReviewEvent;
use App\Events\ManuscriptRecordWithdrawnByAuthor;
use App\Http\Resources\ManuscriptRecordResource;
use App\Models\Journal;
use App\Models\ManagementReviewStep;
use App\Models\ManuscriptRecord;
use App\Models\User;
use App\Rules\UserNotAManuscriptAuthor;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $manuscriptRecord->user_id = auth()->id();
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
            'type' => [new Enum(ManuscriptRecordType::class)],
            'abstract' => 'nullable|string',
            'pls' => 'nullable|string',
            'scientific_implications' => 'nullable|string',
            'regions_and_species' => 'nullable|string',
            'relevant_to' => 'nullable|string',
            'additional_information' => 'nullable|string',
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

    /** Attach a PDF file to this record */
    public function attachPDF(Request $request, ManuscriptRecord $manuscriptRecord): JsonResource
    {
        Gate::authorize('attachManuscript', $manuscriptRecord);

        $validated = $request->validate([
            'pdf' => 'required|file|mimes:pdf',
        ]);

        $manuscriptRecord->addMedia($validated['pdf'])->toMediaCollection('manuscript');

        return $this->defaultResource($manuscriptRecord);
    }

    /** Download PDF attached to this record - return NoContent if empty */
    public function downloadPDF(ManuscriptRecord $manuscriptRecord): mixed
    {
        Gate::authorize('view', $manuscriptRecord);

        $pdf = $manuscriptRecord->getManuscriptFile();

        if ($pdf) {
            return $pdf;
        } else {
            throw new NotFoundHttpException('No PDF attached to this record');
        }
    }

    /** Submit the manuscript record for review */
    public function submitForReview(Request $request, ManuscriptRecord $manuscriptRecord): JsonResource
    {
        Gate::authorize('submitForReview', $manuscriptRecord);

        $validated = $request->validate([
            'reviewer_user_id' => [
                new UserNotAManuscriptAuthor($manuscriptRecord),
                'required',
                'exists:users,id', ],
        ]);

        // validate that the record has all the required fields
        $manuscriptRecord->validateIsFilled();

        // get review user
        $reviewUser = User::findOrFail($validated['reviewer_user_id']);

        // create the first management review step for this record
        $reviewStep = new ManagementReviewStep();
        $reviewStep->manuscript_record_id = $manuscriptRecord->id;
        $reviewStep->user_id = $reviewUser->id;
        $reviewStep->save();

        // trigger event that the record was submitted
        ManuscriptRecordToReviewEvent::dispatch($manuscriptRecord, $reviewUser);

        $manuscriptRecord->status = ManuscriptRecordStatus::IN_REVIEW;
        $manuscriptRecord->sent_for_review_at = now();
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

        ManuscriptRecordSubmitted::dispatch($manuscriptRecord);

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
            'journal_id' => 'required|exists:journals,id',
        ]);

        $manuscriptRecord->status = ManuscriptRecordStatus::ACCEPTED;
        // if the submitted to journal date is given, set it.
        if ($validated['submitted_to_journal_on']) {
            $manuscriptRecord->submitted_to_journal_on = $validated['submitted_to_journal_on'];
        }
        $manuscriptRecord->accepted_on = $validated['accepted_on'];
        $manuscriptRecord->save();

        // create the accepted publication
        $journal = Journal::findOrFail($validated['journal_id']);
        CreatePublicationFromManuscript::handle($manuscriptRecord, $journal);

        ManuscriptRecordAccepted::dispatch($manuscriptRecord);

        return $this->defaultResource($manuscriptRecord);
    }

    /** Default Resource with eager loaded properties */
    private function defaultResource(ManuscriptRecord $manuscriptRecord): JsonResource
    {
        $relationships = collect('user');
        if ($manuscriptRecord->status === ManuscriptRecordStatus::ACCEPTED) {
            $relationships->push('publication');
        }

        return new ManuscriptRecordResource($manuscriptRecord->load($relationships->toArray()));
    }
}
