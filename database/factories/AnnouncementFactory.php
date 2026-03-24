<?php

namespace Database\Factories;

use App\Models\Announcement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Announcement>
 */
class AnnouncementFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title_en' => $this->faker->sentence,
            'title_fr' => $this->faker->sentence,
            'text_en' => $this->faker->paragraph,
            'text_fr' => $this->faker->paragraph,
            'start_at' => $this->faker->dateTimeBetween('-2 week', '-1 week'),
            'end_at' => $this->faker->dateTimeBetween('+1 week', '+2 week'),
        ];
    }
}
