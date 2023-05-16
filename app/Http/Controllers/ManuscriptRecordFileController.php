<?php

namespace App\Http\Controllers;

use App\Http\Resources\MediaResource;
use App\Models\ManuscriptRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ManuscriptRecordFileController extends Controller
{
    /**
     * Returns a list of all files for a manuscript record.
     */
    public function index(Request $request, ManuscriptRecord $manuscriptRecord)
    {
        Gate::authorize('view', $manuscriptRecord);

        $media = $manuscriptRecord->getMedia('manuscript');

        return MediaResource::collection($media);
    }

    /**
     * Save manuscript file
     */
    public function store(Request $request, ManuscriptRecord $manuscriptRecord)
    {
        Gate::authorize('attachManuscript', $manuscriptRecord);

        $validated = $request->validate([
            'pdf' => 'required|file|mimes:pdf',
        ]);

        $media = $manuscriptRecord->addMedia($validated['pdf'])->toMediaCollection('manuscript');

        return MediaResource::create($media);
    }

    /**
     * Display the specified MediaResource for the file or download it.
     */
    public function show(Request $request, ManuscriptRecord $manuscriptRecord, string $uuid)
    {

        Gate::authorize('view', $manuscriptRecord);

        $media = $manuscriptRecord->getMedia('manuscript')->where('uuid', $uuid)->first();

        if (! $media) {
            throw new NotFoundHttpException('File not found.');
        }

        // if request has download query param, download the file
        if ($request->has('download')) {
            return $media;
        } else {
            return MediaResource::make($media);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, ManuscriptRecord $manuscriptRecord, string $uuid)
    {

    }
}
