<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\ManuscriptRecord;
use App\Models\ManuscriptReviewContributor;
use Illuminate\Database\Eloquent\Factories\Factory;
use VincentAuger\OrcidSdk\Enums\PeerReviewRole;

/**
 * @extends Factory<ManuscriptReviewContributor>
 */
class ManuscriptReviewContributorFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'manuscript_record_id' => ManuscriptRecord::factory(),
            'author_id' => Author::factory(),
            'role' => PeerReviewRole::Reviewer,
            'review_date' => $this->faker->date(),
        ];
    }

    /**
     * The contributor edited the manuscript.
     */
    public function editor(): static
    {
        return $this->state(fn (array $attributes): array => [
            'role' => PeerReviewRole::Editor,
        ]);
    }
}
