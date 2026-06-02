<?php

namespace App\Actions;

use App\Enums\ManuscriptRecordStatus;
use App\Events\ManuscriptManagementReviewUnsubmittedEvent;
use App\Models\ManuscriptRecord;
use Illuminate\Support\Facades\DB;

class UnsubmitManuscriptManagementReview
{
    public static function handle(ManuscriptRecord $manuscriptRecord): void
    {
        if ($manuscriptRecord->status !== ManuscriptRecordStatus::IN_REVIEW) {
            throw new \InvalidArgumentException(
                'Only manuscript records in review can be unsubmitted.'
            );
        }

        $reviewUsers = $manuscriptRecord->managementReviewSteps->values(); // store the reviewer steps for contact before deleting the review steps

        DB::transaction(function () use ($manuscriptRecord): void {
            $manuscriptRecord->managementReviewSteps()->update(['previous_step_id' => null]);
            $manuscriptRecord->managementReviewSteps()->delete();

            $manuscriptRecord->status = ManuscriptRecordStatus::DRAFT;
            $manuscriptRecord->sent_for_review_at = null;
            $manuscriptRecord->save();
            $manuscriptRecord->refresh();

            activity()
                ->performedOn($manuscriptRecord)
                ->causedBy(auth()->user())
                ->event('unsubmitted')
                ->log('Manuscript was unsubmitted for manuscript management review');
        });

        event(new ManuscriptManagementReviewUnsubmittedEvent($manuscriptRecord, $reviewUsers)); // trigger email reviewer and author process

    }
}
