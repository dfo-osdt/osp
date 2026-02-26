<?php

namespace App\Actions;

use App\Enums\ManuscriptRecordStatus;
use App\Enums\MediaCollection;
use App\Events\PlanningBinder\RemovePlanningBinderItem;
use App\Models\ManuscriptRecord;
use Illuminate\Support\Facades\DB;

class DeleteSubmittedManuscriptRecord
{
    public static function handle(ManuscriptRecord $manuscriptRecord): void
    {
        if ($manuscriptRecord->status !== ManuscriptRecordStatus::IN_REVIEW) {
            throw new \InvalidArgumentException(
                "Manuscript record must be in IN_REVIEW status to be deleted. Current status: {$manuscriptRecord->status->value}"
            );
        }

        DB::transaction(function () use ($manuscriptRecord): void {
            $manuscriptRecord->managementReviewSteps()->delete();
            $manuscriptRecord->manuscriptAuthors()->delete();
            $manuscriptRecord->peerReviewers()->delete();
            $manuscriptRecord->fundingSources()->forceDelete();
            $manuscriptRecord->shareables()->delete();

            $planningBinderItem = $manuscriptRecord->planningBinderItem;
            if ($planningBinderItem) {
                RemovePlanningBinderItem::commit(planning_binder_item_id: $planningBinderItem->id);
            }

            $manuscriptRecord->clearMediaCollection(MediaCollection::MANUSCRIPT->value);
            $manuscriptRecord->forceDelete();
        });
    }
}
