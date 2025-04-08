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
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'publisher' => $this->faker->company(),
            'issn' => $this->faker->randomNumber(4).'-'.$this->faker->randomNumber(4),
        ];
    }

    /**
     * DFO series will have a bilingual title and "Fisheries and Oceans Canada" as the publisher
     */
    public function dfoSeries()
    {
        return $this->state([
            'publisher' => Journal::$dfoPublisher,
        ]);
    }
}
