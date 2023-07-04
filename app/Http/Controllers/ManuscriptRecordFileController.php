<?php

namespace App\Http\Controllers;

use App\Http\Resources\MediaResource;
use App\Models\ManuscriptRecord;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
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

        $media = $manuscriptRecord->getManuscriptFiles();

        return MediaResource::collection($media);
    }

    /**
     * Save manuscript file
     */
    public function store(Request $request, ManuscriptRecord $manuscriptRecord)
    {
        Gate::authorize('attachManuscript', $manuscriptRecord);

        $validated = $request->validate([
            'pdf' => 'required|file|mimes:pdf|max:50000',
        ]);

        $media = $manuscriptRecord->addManuscriptFile($validated['pdf']);

        return MediaResource::make($media);
    }

    /**
     * Display the specified MediaResource for the file or download it.
     */
    public function show(Request $request, ManuscriptRecord $manuscriptRecord, string $uuid)
    {

        Gate::authorize('view', $manuscriptRecord);

        $media = $manuscriptRecord->getManuscriptFile($uuid);

        if (! $media) {
            throw new NotFoundHttpException('File not found.');
        }

        // does the user want to download the file?
        $download = $request->boolean('download', false);
        if ($download) {
            return $media;
        }

        return MediaResource::make($media);

    }

    /**
     * Delete the specified file.
     */
    public function destroy(Request $request, ManuscriptRecord $manuscriptRecord, string $uuid)
    {
        Gate::authorize('attachManuscript', $manuscriptRecord);

        try {
            $manuscriptRecord->deleteManuscriptFile($uuid);
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
