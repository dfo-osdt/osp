<?php

namespace App\Http\Controllers;

use App\Enums\PublicationStatus;
use App\Http\Resources\MediaResource;
use App\Http\Resources\PublicationResource;
use App\Models\Publication;
use App\Queries\PublicationListQuery;
use App\Rules\Doi;
use App\Traits\PaginationLimitTrait;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;
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
            'doi' => ['nullable', 'string', new Doi],
            'accepted_on' => 'date|nullable',
            'published_on' => 'date|required',
            'embargoed_until' => 'date|nullable',
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
        Gate::authorize('view', $publication);
        $publication->load('journal', 'user', 'publicationAuthors.author', 'publicationAuthors.organization');

        return new PublicationResource($publication);
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
        Gate::authorize('update', $publication);

        // check if user is seeking to change the status from accepted to published
        if (isset($request['status'])) {
            $validatedStatus = $request->validate([
                'status' => new Enum(PublicationStatus::class),
            ]);
            if ($validatedStatus['status'] !== $publication->status->value) {
                switch($validatedStatus['status']) {
                    case PublicationStatus::PUBLISHED->value:
                        $publication->status = PublicationStatus::PUBLISHED;
                        break;
                    default:
                        // likely, only way we're here is someone is having a go at the API directly.
                        throw ValidationException::withMessages(['status' => 'Cannot change status back to accepted']);
                }
            }
        }

        // validate the rest of the request
        $validated = $request->validate([
            'title' => 'sometimes|required|string',
            'journal_id' => 'sometimes|required|exists:journals,id',
            'doi' => ['string', 'nullable', new Doi],
            'accepted_on' => ['date', 'nullable', Rule::when($publication->status === PublicationStatus::PUBLISHED, ['before_or_equal:published_on']), Rule::requiredIf($publication->status === PublicationStatus::ACCEPTED)],
            'published_on' => ['date', 'nullable', 'after_or_equal:accepted_on', Rule::requiredIf($publication->status === PublicationStatus::PUBLISHED)],
            'embargoed_until' => 'date|nullable',
            'is_open_access' => 'boolean',
        ]);

        // update the publication
        $publication->update($validated);

        return new PublicationResource($publication->load('journal', 'user', 'publicationAuthors.author', 'publicationAuthors.organization'));
    }

    /** Attach a PDF file to this publication */
    public function attachPDF(Request $request, Publication $publication)
    {
        Gate::authorize('update', $publication);

        $validated = $request->validate([
            'pdf' => 'required|file|mimes:pdf',
        ]);

        $publication->addMedia($validated['pdf'])->toMediaCollection('publication');

        return new MediaResource($publication->getPublicationFile('publication'));
    }

    /** return pdf media resource if it exists null response otherwise */
    public function getPDFInfo(Request $request, Publication $publication)
    {
        Gate::authorize('view', $publication);

        $media = $publication->getPublicationFile('publication');

        if ($media) {
            return MediaResource::make($media);
        }

        return response()->noContent();
    }

    /** Download PDF attached to this publication - return NoContent if empty */
    public function downloadPDF(Publication $publication)
    {
        Gate::authorize('viewPdf', $publication);

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

    /**
     * Default Resource with eager loaded relationships
     */
    private function defaultResource(Publication $publication)
    {
        $relationship = collect([
            'journal',
            'user',
            'publicationAuthors.author',
            'publicationAuthors.organization',
            'manuscript',
        ]);

        return new PublicationResource($publication->load($relationship->toArray()));
    }
}
