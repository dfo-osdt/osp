<?php

namespace App\Http\Controllers;

use App\Enums\ManuscriptRecordStatus;
use App\Enums\ManuscriptRecordType;
use App\Events\ManuscriptRecordToReviewEvent;
use App\Http\Resources\ManuscriptRecordResource;
use App\Models\ManuscriptRecord;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ManuscriptRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate that we have a type, title, and region_id
        $validated = $request->validate([
            'title' => 'required|max:255',
            'region_id' => 'required|exists:regions,id',
            'type' => ['required', new Enum(ManuscriptRecordType::class)],
        ]);

        $manuscript = new ManuscriptRecord($validated);
        $manuscript->status = ManuscriptRecordStatus::DRAFT;
        $manuscript->user_id = auth()->id();
        $manuscript->save();
        $manuscript->refresh();

        return new ManuscriptRecordResource($manuscript);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ManuscriptRecord  $manuscriptRecord
     * @return \Illuminate\Http\Response
     */
    public function show(ManuscriptRecord $manuscriptRecord)
    {
        Gate::authorize('view', $manuscriptRecord);

        return new ManuscriptRecordResource($manuscriptRecord);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ManuscriptRecord  $manuscriptRecord
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ManuscriptRecord $manuscriptRecord)
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

        return new ManuscriptRecordResource($manuscriptRecord);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ManuscriptRecord  $manuscriptRecord
     * @return \Illuminate\Http\Response
     */
    public function destroy(ManuscriptRecord $manuscriptRecord)
    {
        //
    }

    /** Attach a PDF file to this record */
    public function attachPDF(Request $request, ManuscriptRecord $manuscriptRecord)
    {
        Gate::authorize('update', $manuscriptRecord);

        $validated = $request->validate([
            'pdf' => 'required|file|mimes:pdf',
        ]);

        $manuscriptRecord->addMedia($validated['pdf'])->toMediaCollection('manuscript');

        return new ManuscriptRecordResource($manuscriptRecord);
    }

    /** Download PDF attached to this record - return NoContent if empty */
    public function downloadPDF(ManuscriptRecord $manuscriptRecord)
    {
        Gate::authorize('view', $manuscriptRecord);

        $pdf = $manuscriptRecord->getManuscriptFile();

        if ($pdf) {
            return $pdf; // response()->download($pdf->getPath(), $pdf->file_name);
        } else {
            throw new NotFoundHttpException('No PDF attached to this record');
        }
    }

    /** Submit the manuscript record for review */
    public function submitForReview(ManuscriptRecord $manuscriptRecord)
    {
        Gate::authorize('submitForReview', $manuscriptRecord);

        // validate that the record has all the required fields
        $manuscriptRecord->validateIsFilled();

        $user = User::factory()->create();

        // trigger event that the record was submitted
        ManuscriptRecordToReviewEvent::dispatch($manuscriptRecord, $user);

        $manuscriptRecord->status = ManuscriptRecordStatus::IN_REVIEW;
        $manuscriptRecord->sent_for_review_at = now();
        $manuscriptRecord->save();

        return new ManuscriptRecordResource($manuscriptRecord);
    }
}
