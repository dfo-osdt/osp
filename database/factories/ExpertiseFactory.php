<?php

namespace Database\Factories;

use App\Models\Expertise;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expertise>
 */
class ExpertiseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name_en' => $this->faker->word(),
            'name_fr' => $this->faker->word(),
            'tid' => $this->faker->randomNumber(4),
            'uuid' => $this->faker->uuid(),
            'parent_tid' => null,
            'parent_uuid' => null,
        ];
    }

    // Create a parent and child expertise pair
    public function withChildren($n = 2): ExpertiseFactory
    {
        return $this->afterCreating(function (Expertise $expertise) use ($n) {
            Expertise::factory()->count(2)->create([
                'parent_tid' => $expertise->tid,
                'parent_uuid' => $expertise->uuid,
            ]);
        });
    }
}
