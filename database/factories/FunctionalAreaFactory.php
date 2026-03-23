<?php

namespace Database\Factories;

use App\Models\FunctionalArea;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FunctionalArea>
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
