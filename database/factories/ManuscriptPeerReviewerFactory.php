<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\ManuscriptPeerReviewer;
use App\Models\ManuscriptRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ManuscriptPeerReviewer>
 */
class ManuscriptPeerReviewerFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'manuscript_record_id' => ManuscriptRecord::factory(),
            'author_id' => Author::factory(),
            'review_date' => $this->faker->date(),
        ];
    }
}
