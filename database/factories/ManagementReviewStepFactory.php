<?php

namespace Database\Factories;

use App\Enums\ManuscriptRecordStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ManagementReviewStep>
 */
class ManagementReviewStepFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition()
    {
        return [
            'manuscript_record_id' => \App\Models\ManuscriptRecord::factory()
                ->filled()
                ->create(['status' => ManuscriptRecordStatus::IN_REVIEW])
                ->id,
            'user_id' => \App\Models\User::factory(),
            'completed_at' => null,
            'comments' => null,
            'status' => \App\Enums\ManagementReviewStepStatus::PENDING,
            'decision' => \App\Enums\ManagementReviewStepDecision::NONE,
            'decision_expected_by' => now()->addBusinessDay(config('osp.management_review.decision_expected_business_days')),
        ];
    }

    /**
     * A management review step that has been approved and completed
     */
    public function approved()
    {
        return $this->state([
            'status' => \App\Enums\ManagementReviewStepStatus::COMPLETED,
            'decision' => \App\Enums\ManagementReviewStepDecision::APPROVED,
            'comments' => 'This manuscript is approved - great job!',
            'completed_at' => now(),
        ]);
    }

    /**
     * A withheld management review step
     */
    public function withheld()
    {
        return $this->state([
            'status' => \App\Enums\ManagementReviewStepStatus::COMPLETED,
            'decision' => \App\Enums\ManagementReviewStepDecision::WITHHELD,
            'comments' => 'This manuscript is not ready for publication',
            'completed_at' => now(),
        ]);
    }
}
