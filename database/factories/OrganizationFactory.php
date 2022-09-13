<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Organization>
 */
class OrganizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'is_validated' => true,
            'name_en' => $this->faker->unique()->sentence(4),
            'name_fr' => $this->faker->unique()->sentence(4),
            'abbr_en' => Str::upper($this->faker->word()),
            'abbr_fr' => Str::upper($this->faker->word()),
        ];
    }
}
