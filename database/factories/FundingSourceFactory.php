<?php

namespace Database\Factories;

use App\Models\Funder;
use App\Models\FundingSource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FundingSource>
 */
class FundingSourceFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition()
    {
        return [
            'title' => $this->faker->unique()->words(3, true),
            'description' => $this->faker->unique()->words(3, true),
            // random id from funder table
            'funder_id' => Funder::inRandomOrder()->first()->id,
        ];
    }
}
