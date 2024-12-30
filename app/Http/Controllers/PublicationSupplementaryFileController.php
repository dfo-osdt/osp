<?php

namespace App\Http\Controllers;

use App\Enums\SupplementaryFileType;
use App\Http\Resources\MediaResource;
use App\Models\Publication;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PublicationSupplementaryFileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Publication $publication)
    {
        Gate::authorize('view', $publication);

        $media = $publication->getMedia('supplementary_file');

        // get publication model and all required relations
        // for the policies to avoid n+1
        $publication->load([
            'manuscriptRecord' => [
                'manuscriptAuthors.author',
                'managementReviewSteps',
                'shareables',
            ],
            'publicationAuthors.author',
        ]);

        $media->each(fn ($media) => $media->setRelation('model', $publication));

        return MediaResource::collection($media->sortBy('created_at', SORT_REGULAR, true));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Publication $publication)
    {
        Gate::authorize('update', $publication);

        $validated = $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:pdf,doc,docx',
                'max:'.(config('media-library.max_file_size') / 1024),
            ],
            'supplementary_file_type' => ['required', Rule::enum(SupplementaryFileType::class)],
            'description' => 'nullable|string|max:200',
        ]);

        $type = SupplementaryFileType::tryFrom($validated['supplementary_file_type']);
        $media = $publication->addSupplementaryFile($validated['file'], $type, $validated['description'] ?? null);

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

        $media = $publication->getSupplementaryFile($uuid);

        if (! $media) {
            throw new NotFoundHttpException('File not found.');
        }

        $download = $request->query('download');
        if ($download) {
            Gate::authorize('downloadMedia', [$publication, $media])->denyAsNotFound();

            return $media;
        }

        $media->setRelation('model', $publication);

        return MediaResource::make($media);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publication $publication, string $uuid)
    {
        Gate::authorize('update', $publication);

        try {
            $publication->deleteSupplementaryFile($uuid);
        } catch (FileNotFoundException $e) {
            throw new NotFoundHttpException('File not found.');
        } catch (Exception $e) {
            return response()->json([
                'message' => 'This file is locked.',
            ], 403);
        }

        return response()->noContent();
    }
}
