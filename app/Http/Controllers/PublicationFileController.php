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
        Gate::authorize('view', $publication);

        $media = $publication->getMedia('publication');

        return MediaResource::collection($media->sortBy('created_at', SORT_REGULAR, true));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Publication $publication)
    {
        Gate::authorize('update', $publication);

        $validated = $request->validate([
            'pdf' => 'required|file|mimes:pdf|max:50000',
        ]);

        $media = $publication->addPublicationFile($validated['pdf']);

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
        Gate::authorize('view', $publication);

        $media = $publication->getMedia('publication')->where('uuid', $uuid)->first();

        $download = $request->query('download', false);
        if ($download && Gate::allows('viewPdf', $publication)) {
            return $media;
        }

        return MediaResource::make($media);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publication $publication, string $uuid)
    {
        Gate::authorize('update', $publication);

        try {
            $publication->deletePublicationFile($uuid);
        } catch (FileNotFoundException $e) {
            throw new NotFoundHttpException('File not found.');
        } catch (Exception $e) {
            return response()->json([
                'message' => 'This file is locked.',
            ], 403);
        }
    }
}
