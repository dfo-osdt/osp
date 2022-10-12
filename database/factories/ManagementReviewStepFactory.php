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
     *
     * @return array<string, mixed>
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
        ];
    }
}
