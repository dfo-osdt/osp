<?php

namespace Database\Factories;

use App\Models\HelpfulLink;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HelpfulLink>
 */
class HelpfulLinkFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title_en' => $this->faker->sentence(3),
            'title_fr' => $this->faker->sentence(3),
            'description_en' => $this->faker->sentence(10),
            'description_fr' => $this->faker->sentence(10),
            'url_en' => $this->faker->url(),
            'url_fr' => $this->faker->url(),
        ];
    }
}
