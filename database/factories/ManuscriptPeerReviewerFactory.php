<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\ManuscriptRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ManuscriptPeerReviewer>
 */
class ManuscriptPeerReviewerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
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
