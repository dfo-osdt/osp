<?php

namespace Database\Factories;

use App\Enums\ManagementReviewStepDecision;
use App\Enums\ManagementReviewStepStatus;
use App\Enums\ManuscriptRecordStatus;
use App\Models\ManagementReviewStep;
use App\Models\ManuscriptRecord;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ManagementReviewStep>
 */
class ManagementReviewStepFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition()
    {
        return [
            'manuscript_record_id' => ManuscriptRecord::factory()
                ->filled()
                ->create(['status' => ManuscriptRecordStatus::IN_REVIEW])
                ->id,
            'user_id' => User::factory(),
            'completed_at' => null,
            'comments' => null,
            'status' => ManagementReviewStepStatus::PENDING,
            'decision' => ManagementReviewStepDecision::NONE,
            'decision_expected_by' => now()->addBusinessDay(config('osp.management_review.decision_expected_business_days')),
        ];
    }

    /**
     * A management review step that has been approved and completed
     */
    public function approved()
    {
        return $this->state([
            'status' => ManagementReviewStepStatus::COMPLETED,
            'decision' => ManagementReviewStepDecision::COMPLETE,
            'comments' => 'This manuscript is approved - great job!',
            'completed_at' => now(),
        ]);
    }
}
