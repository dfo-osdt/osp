<?php

namespace App\Actions;

use App\Enums\ManuscriptRecordStatus;
use App\Events\ManuscriptManagementReviewUnsubmittedEvent;
use App\Models\ManuscriptRecord;
use Illuminate\Support\Facades\DB;

class UnsubmitManuscriptManagementReview
{
    public static function handle(ManuscriptRecord $manuscriptRecord, string $reason): void
    {
        if ($manuscriptRecord->status !== ManuscriptRecordStatus::IN_REVIEW) {
            throw new \InvalidArgumentException(
                'Only manuscript records in review can be unsubmitted.'
            );
        }

        $old = [
            'status' => $manuscriptRecord->status->value,
            'sent_for_review_at' => $manuscriptRecord->sent_for_review_at,
            'management_review_steps' => $manuscriptRecord->managementReviewSteps
                ->map(fn ($reviewStep): array => [
                    'user_id' => $reviewStep->user_id,
                    'decision' => $reviewStep->decision?->value,
                    'comments' => $reviewStep->comments,
                ])
                ->values()
                ->all(),
        ];

        $reason = trim($reason);
        $reviewUsers = $manuscriptRecord->managementReviewSteps->values();

        DB::transaction(function () use ($manuscriptRecord, $reason, $old): void {
            $manuscriptRecord->managementReviewSteps()->update(['previous_step_id' => null]);
            $manuscriptRecord->managementReviewSteps()->delete();

            $manuscriptRecord->status = ManuscriptRecordStatus::DRAFT;
            $manuscriptRecord->sent_for_review_at = null;
            $manuscriptRecord->unlockManuscriptFiles();
            $manuscriptRecord->save();
            $manuscriptRecord->refresh();

            activity()
                ->performedOn($manuscriptRecord)
                ->causedBy(auth()->user())
                ->event('unsubmitted')
                ->withProperties([
                    'reason' => $reason,
                    'old' => $old,
                    'attributes' => [
                        'status' => $manuscriptRecord->status->value,
                        'sent_for_review_at' => $manuscriptRecord->sent_for_review_at,
                    ],
                ])
                ->log('Manuscript was unsubmitted for manuscript management review');
        });

        event(new ManuscriptManagementReviewUnsubmittedEvent($manuscriptRecord, $reviewUsers));
    }
}
