<?php

namespace App\Http\Controllers;

use App\Http\Resources\PublicationResource;
use App\Models\Publication;
use App\Queries\PublicationListQuery;
use App\Rules\Doi;
use App\Traits\PaginationLimitTrait;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PublicationController extends Controller
{
    use PaginationLimitTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $this->getLimitFromRequest($request);
        $publicationListQuery = new PublicationListQuery($request);

        return PublicationResource::collection($publicationListQuery->paginate($limit));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // can this user create a publication?
        $this->authorize('create', Publication::class);

        // validate the request
        $validated = $request->validate([
            'status' => new Enum(PublicationStatus::class),
            'title' => 'required',
            'journal_id' => 'required|exists:journals,id',
            'doi' => ['string', 'required', new Doi],
            'accepted_on' => 'date|required',
            'published_on' => 'date',
            'embargoed_until' => 'date',
            'is_open_access' => 'boolean',
        ]);

        // create the publication
        $publication = Publication::make($validated);
        $publication->user_id = $request->user()->id;
        $publication->save();

        return new PublicationResource($publication);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Publication  $publication
     * @return \Illuminate\Http\Response
     */
    public function show(Publication $publication)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Publication  $publication
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Publication $publication)
    {
        //
    }

    /** Attach a PDF file to this publication */
    public function attachPDF(Request $request, Publication $publication)
    {
        Gate::authorize('update', $publication);

        $validated = $request->validate([
            'pdf' => 'required|file|mimes:pdf',
        ]);

        $publication->addMedia($validated['pdf'])->toMediaCollection('publication');

        return new PublicationResource($publication->load('user'));
    }

    /** Download PDF attached to this publication - return NoContent if empty */
    public function downloadPDF(Publication $publication)
    {
        Gate::authorize('view', $publication);

        $pdf = $publication->getPublicationFile();

        if ($pdf) {
            return $pdf;
        } else {
            throw new NotFoundHttpException('No PDF attached to this publication');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Publication  $publication
     * @return \Illuminate\Http\Response
     */
    public function destroy(Publication $publication)
    {
        //
    }
}
