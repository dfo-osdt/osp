<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Funder>
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
