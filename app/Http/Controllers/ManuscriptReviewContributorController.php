<?php

namespace App\Http\Controllers;

use App\Enums\ManuscriptRecordType;
use App\Http\Resources\ManuscriptReviewContributorResource;
use App\Jobs\SyncReviewContributionWithOrcid;
use App\Models\ManuscriptRecord;
use App\Models\ManuscriptReviewContributor;
use App\Traits\LoadsManuscriptRecordPolicyRelationships;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use VincentAuger\OrcidSdk\Enums\PeerReviewRole;

class ManuscriptReviewContributorController extends Controller
{
    use LoadsManuscriptRecordPolicyRelationships;

    /**
     * Load relationships needed for the resource and policies
     */
    private function loadResourceRelationships(ManuscriptReviewContributor $manuscriptReviewContributor, ManuscriptRecord $manuscriptRecord): ManuscriptReviewContributor
    {
        $manuscriptReviewContributor->setRelation('manuscriptRecord', $manuscriptRecord);
        $manuscriptReviewContributor->load('author.organization');

        return $manuscriptReviewContributor;
    }

    /**
     * The contributor is bound by id alone, so make sure it belongs to the
     * manuscript in the URL before authorizing against that manuscript.
     */
    private function ensureBelongsToManuscript(ManuscriptReviewContributor $manuscriptReviewContributor, ManuscriptRecord $manuscriptRecord): void
    {
        abort_unless($manuscriptReviewContributor->manuscript_record_id === $manuscriptRecord->id, 404);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(ManuscriptRecord $manuscriptRecord): JsonResource
    {

        $manuscriptRecord->load($this->getManuscriptRecordPolicyRelationships());

        Gate::authorize('view', $manuscriptRecord);

        return ManuscriptReviewContributorResource::collection(
            $manuscriptRecord->reviewContributors()
                ->with('author.organization')
                ->chaperone('manuscriptRecord')
                ->get()
        );

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ManuscriptRecord $manuscriptRecord, Request $request): JsonResource
    {
        $manuscriptRecord->load($this->getManuscriptRecordPolicyRelationships());
        Gate::authorize('update', $manuscriptRecord);

        if ($manuscriptRecord->type === ManuscriptRecordType::PRIMARY) {
            throw ValidationException::withMessages(['manuscript_record_id' => 'A third-party primary manuscript cannot have review contributors']);
        }

        $validated = $request->validate([
            'author_id' => ['required', 'exists:authors,id'],
            'role' => ['required', Rule::enum(PeerReviewRole::class)->only(ManuscriptReviewContributor::allowedRoles())],
        ]);

        if ($manuscriptRecord->reviewContributors()->where('author_id', $validated['author_id'])->exists()) {
            throw ValidationException::withMessages(['author_id' => 'This author is already a review contributor for this manuscript']);
        }

        if ($manuscriptRecord->manuscriptAuthors()->where('author_id', $validated['author_id'])->exists()) {
            throw ValidationException::withMessages(['author_id' => 'This author is already on the list of manuscript authors']);
        }

        $manuscriptReviewContributor = new ManuscriptReviewContributor($validated);
        $manuscriptReviewContributor->manuscript_record_id = $manuscriptRecord->id;
        $manuscriptReviewContributor->review_date = now();
        $manuscriptReviewContributor->save();

        activity()
            ->performedOn($manuscriptRecord)
            ->withProperties([
                'author_id' => $manuscriptReviewContributor->author_id,
                'role' => $manuscriptReviewContributor->role->value,
            ])
            ->causedBy($request->user())
            ->log('manuscript.review-contributor.created');

        $this->loadResourceRelationships($manuscriptReviewContributor, $manuscriptRecord);

        return new ManuscriptReviewContributorResource($manuscriptReviewContributor);

    }

    /**
     * Display the specified resource.
     */
    public function show(ManuscriptRecord $manuscriptRecord, ManuscriptReviewContributor $manuscriptReviewContributor): void
    {
        $this->ensureBelongsToManuscript($manuscriptReviewContributor, $manuscriptRecord);
        $manuscriptRecord->load($this->getManuscriptRecordPolicyRelationships());
        Gate::authorize('view', $manuscriptReviewContributor);
    }

    /**
     * Change the contributor's role on this manuscript.
     */
    public function update(ManuscriptRecord $manuscriptRecord, ManuscriptReviewContributor $manuscriptReviewContributor, Request $request): JsonResource
    {
        $this->ensureBelongsToManuscript($manuscriptReviewContributor, $manuscriptRecord);
        $manuscriptRecord->load($this->getManuscriptRecordPolicyRelationships());
        $manuscriptReviewContributor->setRelation('manuscriptRecord', $manuscriptRecord);
        Gate::authorize('update', $manuscriptReviewContributor);

        $validated = $request->validate([
            'role' => ['required', Rule::enum(PeerReviewRole::class)->only(ManuscriptReviewContributor::allowedRoles())],
        ]);

        $oldRole = $manuscriptReviewContributor->role;
        $manuscriptReviewContributor->update($validated);

        if ($manuscriptReviewContributor->wasChanged('role')) {
            SyncReviewContributionWithOrcid::dispatchForChanged(new Collection([$manuscriptReviewContributor]));
        }

        activity()
            ->performedOn($manuscriptRecord)
            ->withProperties([
                'author_id' => $manuscriptReviewContributor->author_id,
                'role' => $manuscriptReviewContributor->role->value,
                'old_role' => $oldRole->value,
            ])
            ->causedBy($request->user())
            ->log('manuscript.review-contributor.updated');

        $this->loadResourceRelationships($manuscriptReviewContributor, $manuscriptRecord);

        return new ManuscriptReviewContributorResource($manuscriptReviewContributor);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ManuscriptRecord $manuscriptRecord, ManuscriptReviewContributor $manuscriptReviewContributor, Request $request)
    {
        $this->ensureBelongsToManuscript($manuscriptReviewContributor, $manuscriptRecord);
        $manuscriptRecord->load($this->getManuscriptRecordPolicyRelationships());
        Gate::authorize('delete', $manuscriptReviewContributor);

        // soft delete first: the sync job removes the ORCID item, then the contributor
        $manuscriptReviewContributor->delete();

        dispatch(new SyncReviewContributionWithOrcid($manuscriptReviewContributor));

        activity()
            ->performedOn($manuscriptRecord)
            ->withProperties([
                'author_id' => $manuscriptReviewContributor->author_id,
                'role' => $manuscriptReviewContributor->role->value,
            ])
            ->causedBy($request->user())
            ->log('manuscript.review-contributor.removed');

        return response()->noContent();
    }
}
