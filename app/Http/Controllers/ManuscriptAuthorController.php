<?php

namespace App\Http\Controllers;

use App\Http\Resources\ManuscriptAuthorResource;
use App\Models\ManuscriptAuthor;
use App\Models\ManuscriptRecord;
use App\Traits\LoadsManuscriptRecordPolicyRelationships;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ManuscriptAuthorController extends Controller
{
    use LoadsManuscriptRecordPolicyRelationships;

    /**
     * Display a listing of the resource.
     */
    public function index(ManuscriptRecord $manuscriptRecord): JsonResource
    {
        $manuscriptRecord->load($this->getManuscriptRecordPolicyRelationships());
        Gate::authorize('view', $manuscriptRecord);

        $manuscriptAuthors = $manuscriptRecord->manuscriptAuthors()->with($this->getPolicyRelationships())->orderBy('id')->get();

        return ManuscriptAuthorResource::collection($manuscriptAuthors);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ManuscriptRecord $manuscriptRecord): JsonResource
    {
        $manuscriptRecord->load($this->getManuscriptRecordPolicyRelationships());
        Gate::authorize('update', $manuscriptRecord);

        $validated = $request->validate([
            'author_id' => [
                'required',
                'integer',
                'exists:authors,id',
                Rule::unique('manuscript_authors')->where(fn ($query) => $query->where('manuscript_record_id', $manuscriptRecord->id)),
            ],
            'is_corresponding_author' => ['boolean'],
        ]);

        $author = \App\Models\Author::query()->find($validated['author_id']);

        $manuscriptAuthor = new ManuscriptAuthor;
        $manuscriptAuthor->manuscript_record_id = $manuscriptRecord->id;
        $manuscriptAuthor->author_id = $validated['author_id'];
        $manuscriptAuthor->is_corresponding_author = $validated['is_corresponding_author'] ?? false;
        $manuscriptAuthor->organization_id = $author->organization_id;
        $manuscriptAuthor->save();
        $manuscriptAuthor->load($this->getPolicyRelationships());

        return ManuscriptAuthorResource::make($manuscriptAuthor);
    }

    /**
     * Display the specified resource.
     */
    public function show(ManuscriptRecord $manuscriptRecord, ManuscriptAuthor $manuscriptAuthor): JsonResource
    {
        $manuscriptRecord->load($this->getManuscriptRecordPolicyRelationships());
        Gate::authorize('view', $manuscriptRecord);

        $manuscriptAuthor->load($this->getPolicyRelationships());

        return ManuscriptAuthorResource::make($manuscriptAuthor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ManuscriptRecord $manuscriptRecord, ManuscriptAuthor $manuscriptAuthor): JsonResource
    {
        $manuscriptRecord->load($this->getManuscriptRecordPolicyRelationships());
        Gate::authorize('update', $manuscriptRecord);

        $validated = $request->validate([
            // 'author_id' => 'integer|exists:authors,id',
            'is_corresponding_author' => ['boolean'],
            'organization_id' => ['integer', 'exists:organizations,id'],
        ]);

        if (isset($validated['organization_id'])) {
            $manuscriptAuthor->organization_id = $validated['organization_id'];
        }
        if (isset($validated['is_corresponding_author'])) {
            $manuscriptAuthor->is_corresponding_author = $validated['is_corresponding_author'];
        }
        $manuscriptAuthor->save();
        $manuscriptAuthor->setRelation('manuscriptRecord', $manuscriptRecord);
        $manuscriptAuthor->refresh()->load($this->getPolicyRelationships());

        return ManuscriptAuthorResource::make($manuscriptAuthor);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ManuscriptRecord $manuscriptRecord, ManuscriptAuthor $manuscriptAuthor): Response
    {
        $manuscriptRecord->load($this->getManuscriptRecordPolicyRelationships());
        Gate::authorize('delete', $manuscriptAuthor);

        $manuscriptAuthor->delete();

        // response 204
        return response()->noContent();
    }

    /**
     * This returns the require relationships so that we authorize
     * via the policy without lazy loading relationships later.
     */
    private function getPolicyRelationships(): array
    {
        return [
            'author',
            'organization',
            'manuscriptRecord' => [
                'user',
                'shareables',
                'manuscriptAuthors.author',
            ],
        ];
    }
}
