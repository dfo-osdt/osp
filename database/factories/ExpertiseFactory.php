<?php

namespace Database\Factories;

use App\Models\Expertise;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Expertise>
 */
class ExpertiseFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name_en' => $this->faker->unique()->sentence(3),
            'name_fr' => $this->faker->unique()->sentence(3),
            'is_validated' => false,
        ];
    }

    /**
     * Indicate that the expertise is validated.
     */
    public function validated(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_validated' => true,
        ]);
    }
}
