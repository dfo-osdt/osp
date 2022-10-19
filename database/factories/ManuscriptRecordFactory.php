<?php

namespace Database\Factories;

use App\Enums\ManuscriptRecordStatus;
use App\Enums\ManuscriptRecordType;
use App\Models\ManuscriptAuthor;
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
            'pls' => $this->faker->paragraph(),
            'scientific_implications' => $this->faker->paragraph(),
            'regions_and_species' => $this->faker->paragraph(),
            'relevant_to' => $this->faker->paragraph(),
            'additional_information' => $this->faker->paragraph(),
        ])->afterCreating(function ($manuscript) {
            $manuscript->manuscriptAuthors()->save(ManuscriptAuthor::factory()->make(['is_corresponding_author' => true])); // create a corresponding author
            $manuscript->manuscriptAuthors()->saveMany(ManuscriptAuthor::factory()->count(3)->make()); // create 3 other authors
            $manuscript->addMedia(getcwd().'/database/factories/files/BieberFever.pdf')->preservingOriginal()->toMediaCollection('manuscript'); // add a manuscript file
        });
    }

    /**
     * A manuscript record that has been submitted for internal review
     */
    public function in_review()
    {
        return $this->filled()->state([
            'status' => ManuscriptRecordStatus::IN_REVIEW,
        ]);
    }
}
