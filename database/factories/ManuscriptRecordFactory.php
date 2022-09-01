<?php

namespace Database\Factories;

use App\Enums\ManuscriptRecordStatus;
use App\Enums\ManuscriptRecordType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ManuscriptRecord>
 */
class ManuscriptRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'type' => ManuscriptRecordType::PRIMARY,
            'status' => ManuscriptRecordStatus::DRAFT,
            'title' => $this->faker->sentence(),
            'region_id' => $this->faker->numberBetween(1, 8),
            'user_id' => User::factory(),
        ];
    }

    /**
     * A fully filled out manuscript record, ready to be submitted for internal review
     */
    public function filled()
    {
        return $this->state([
            'abstract' => $this->faker->paragraph(),
            'pls_en' => $this->faker->paragraph(),
            'pls_fr' => $this->faker->paragraph(),
            'scientific_implications' => $this->faker->paragraph(),
            'regions_and_species' => $this->faker->paragraph(),
            'relevant_to' => $this->faker->paragraph(),
            'additional_information' => $this->faker->paragraph(),
        ]);
    }
}
