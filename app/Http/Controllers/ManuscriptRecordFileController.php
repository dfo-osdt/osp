<?php

namespace App\Http\Controllers;

use App\Http\Resources\MediaResource;
use App\Models\ManuscriptRecord;
use App\Traits\LoadsManuscriptRecordPolicyRelationships;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ManuscriptRecordFileController extends Controller
{
    use LoadsManuscriptRecordPolicyRelationships;

    /**
     * Returns a list of all files for a manuscript record.
     */
    public function index(Request $request, ManuscriptRecord $manuscriptRecord)
    {
        $manuscriptRecord->load($this->getManuscriptRecordPolicyRelationships());
        Gate::authorize('view', $manuscriptRecord);

        $media = $manuscriptRecord->getManuscriptFiles();

        // get manuscript model and all required relations to avoid n+1
        $media->each(fn ($media) => $media->setRelation('model', $manuscriptRecord));

        return MediaResource::collection($media->sortBy('created_at', SORT_REGULAR, true));
    }

    /**
     * Save manuscript file
     */
    public function store(Request $request, ManuscriptRecord $manuscriptRecord)
    {
        $manuscriptRecord->load($this->getManuscriptRecordPolicyRelationships());
        Gate::authorize('attachManuscript', $manuscriptRecord);

        $validated = $request->validate([
            'pdf' => [
                'required',
                'file',
                'mimes:pdf',
                'max:'.(config('media-library.max_file_size') / 1024),
            ],
        ]);

        $media = $manuscriptRecord->addManuscriptFile($validated['pdf']);

        activity()
            ->performedOn($manuscriptRecord)
            ->withProperties($media->toArray())
            ->causedBy($request->user())
            ->log('manuscript.file.uploaded');

        $media->setRelation('model', $manuscriptRecord);

        return MediaResource::make($media);
    }

    /**
     * Display the specified MediaResource for the file or download it.
     */
    public function show(Request $request, ManuscriptRecord $manuscriptRecord, string $uuid)
    {
        $manuscriptRecord->load($this->getManuscriptRecordPolicyRelationships());
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

        $media->setRelation('model', $manuscriptRecord);

        return MediaResource::make($media);
    }

    /**
     * Delete the specified file.
     */
    public function destroy(Request $request, ManuscriptRecord $manuscriptRecord, string $uuid)
    {
        $manuscriptRecord->load($this->getManuscriptRecordPolicyRelationships());
        Gate::authorize('attachManuscript', $manuscriptRecord);

        try {
            $manuscriptRecord->deleteManuscriptFile($uuid);
        } catch (FileNotFoundException) {
            throw new NotFoundHttpException('File not found.');
        } catch (Exception) {
            return response()->json([
                'message' => 'This file is locked.',
            ], 403);
        }

        activity()
            ->performedOn($manuscriptRecord)
            ->withProperties(['uuid' => $uuid])
            ->causedBy($request->user())
            ->log('manuscript.file.deleted');

        return response()->noContent();
    }
}
