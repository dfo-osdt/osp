<?php

namespace App\Http\Controllers;

use App\Http\Resources\MediaResource;
use App\Models\Publication;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PublicationFileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Publication $publication)
    {
        // Load relationships needed for PublicationPolicy
        $publication->load([
            'manuscriptRecord' => [
                'manuscriptAuthors.author',
                'managementReviewSteps',
                'shareables',
            ],
            'publicationAuthors.author',
            'region',
        ]);
        
        Gate::authorize('view', $publication);

        $media = $publication->getMedia('publication');

        $media->each(fn ($media) => $media->setRelation('model', $publication));

        return MediaResource::collection($media->sortBy('created_at', SORT_REGULAR, true));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Publication $publication): \App\Http\Resources\MediaResource
    {
        // Load relationships needed for PublicationPolicy
        $publication->load('publicationAuthors.author', 'region');
        Gate::authorize('update', $publication);

        $validated = $request->validate([
            'pdf' => [
                'required',
                'file',
                'mimes:pdf',
                'max:'.(config('media-library.max_file_size') / 1024),
            ],
        ]);

        $media = $publication->addPublicationFile($validated['pdf']);

        // Load relationships on publication for MediaResource policy checks
        $publication->load('publicationAuthors.author', 'region');
        $media->setRelation('model', $publication);

        activity()
            ->performedOn($publication)
            ->withProperties($media->toArray())
            ->causedBy($request->user())
            ->log('publication.file.uploaded');

        return new MediaResource($media);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Publication $publication, string $uuid)
    {
        // Load relationships needed for PublicationPolicy
        $publication->load([
            'manuscriptRecord' => [
                'manuscriptAuthors.author',
                'managementReviewSteps',
                'shareables',
            ],
            'publicationAuthors.author',
            'region',
        ]);
        
        Gate::authorize('view', $publication);

        $media = $publication->getPublicationFile($uuid);

        if (! $media) {
            throw new NotFoundHttpException('File not found.');
        }

        $media->setRelation('model', $publication);

        $download = $request->query('download');
        if ($download && Gate::allows('downloadMedia', [$publication, $media])) {
            return $media;
        }

        return MediaResource::make($media);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publication $publication, string $uuid)
    {
        // Load relationships needed for PublicationPolicy
        $publication->load('publicationAuthors.author', 'region');
        Gate::authorize('update', $publication);

        try {
            $publication->deletePublicationFile($uuid);
        } catch (FileNotFoundException) {
            throw new NotFoundHttpException('File not found.');
        } catch (Exception) {
            return response()->json([
                'message' => 'This file is locked.',
            ], 403);
        }

        return response()->noContent();
    }
}
