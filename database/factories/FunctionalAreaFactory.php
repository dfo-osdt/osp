<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FunctionalArea>
 */
class FunctionalAreaFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name_en' => $this->faker->word(),
            'name_fr' => $this->faker->word(),
        ];
    }
}
