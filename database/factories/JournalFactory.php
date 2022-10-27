<?php

namespace Database\Factories;

use App\Models\Journal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Journal>
 */
class JournalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title_en' => $this->faker->sentence(),
            'publisher' => $this->faker->company(),
        ];
    }

    /**
     * DFO series will have a bilingual title and "Fisheries and Oceans Canada" as the publisher
     */
    public function dfoSeries()
    {
        return $this->state([
            'title_fr' => $this->faker->sentence(),
            'publisher' => Journal::$dfoPublisher,
        ]);
    }
}
