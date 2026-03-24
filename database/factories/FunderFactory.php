<?php

namespace Database\Factories;

use App\Models\Funder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Funder>
 */
class FunderFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition()
    {
        return [
            'name_en' => $this->faker->unique()->words(3, true),
            'name_fr' => $this->faker->unique()->words(3, true),
            'organization_id' => null,
        ];
    }
}
