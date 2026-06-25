<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\ManuscriptRecordStatus;
use App\Enums\ManuscriptRecordType;
use App\Enums\Permissions\UserPermission;
use App\Enums\PublicationStatus;
use App\Http\Resources\EditorQueuePublicationResource;
use App\Models\ManuscriptRecord;
use App\Models\Publication;
use App\Traits\PaginationLimitTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * National overview for editors and chief editors of the EOS (secondary)
 * publication pipeline. Surfaces a glanceable set of counts together with the
 * "due queue" of secondary publications accepted in the Single Window that are
 * awaiting publication.
 */
class EditorDashboardController extends Controller
{
    use PaginationLimitTrait;

    public function __invoke(Request $request): ResourceCollection
    {
        abort_unless(
            $request->user()->hasPermissionTo(UserPermission::UPDATE_PUBLICATIONS),
            403
        );

        $limit = $this->getLimitFromRequest($request);

        // The due queue: secondary publications accepted in the Single Window
        // but not yet published, oldest first so the most overdue are on top.
        $queue = Publication::query()
            ->secondaryPublication()
            ->where('status', PublicationStatus::ACCEPTED)
            ->with([
                'journal',
                'region',
                'planningBinderItem',
                'publicationAuthors.author',
            ])
            ->oldest('accepted_on')
            ->orderBy('id')
            ->paginate($limit)
            ->appends($request->query());

        return EditorQueuePublicationResource::collection($queue)
            ->additional(['counts' => $this->counts()]);
    }

    /**
     * Top-line counts for the editor dashboard stat strip.
     *
     * @return array{awaiting_single_window: int, in_single_window: int, in_planning_binder: int}
     */
    private function counts(): array
    {
        // Secondary MRFs management has approved (reviewed) but the author has
        // not yet submitted to the Single Window (which marks them accepted).
        $awaitingSingleWindow = ManuscriptRecord::query()
            ->where('type', ManuscriptRecordType::SECONDARY)
            ->where('status', ManuscriptRecordStatus::REVIEWED)
            ->count();

        // Secondary publications accepted in the Single Window, awaiting publication.
        $inSingleWindow = Publication::query()
            ->secondaryPublication()
            ->where('status', PublicationStatus::ACCEPTED)
            ->count();

        // Of those, how many are tracked in the planning binder.
        $inPlanningBinder = Publication::query()
            ->secondaryPublication()
            ->where('status', PublicationStatus::ACCEPTED)
            ->whereHas('planningBinderItem')
            ->count();

        return [
            'awaiting_single_window' => $awaitingSingleWindow,
            'in_single_window' => $inSingleWindow,
            'in_planning_binder' => $inPlanningBinder,
        ];
    }
}
