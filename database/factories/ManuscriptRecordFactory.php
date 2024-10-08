<?php

namespace Database\Factories;

use App\Enums\ManuscriptRecordStatus;
use App\Enums\ManuscriptRecordType;
use App\Models\FunctionalArea;
use App\Models\FundingSource;
use App\Models\ManuscriptAuthor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

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
            'ulid' => Str::ulid(),
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
            'title' => 'A fully filled out manuscript record, ready to be submitted for internal review',
            'abstract' => $this->faker->paragraph(),
            'pls' => $this->faker->paragraph(),
            'relevant_to' => $this->faker->paragraph(),
            'potential_public_interest' => $this->faker->boolean(),
            'additional_information' => $this->faker->paragraph(),
            'functional_area_id' => FunctionalArea::factory()->create()->id,
        ])->afterCreating(function ($manuscript) {
            $manuscript->manuscriptAuthors()->save(ManuscriptAuthor::factory()->make(['is_corresponding_author' => true])); // create a corresponding author
            $manuscript->manuscriptAuthors()->saveMany(ManuscriptAuthor::factory()->count(3)->make()); // create 3 other authors
            $manuscript->fundingSources()->saveMany(FundingSource::factory()->count(3)->make()); // create 3 funding sources
            $manuscript->addManuscriptFile(getcwd().'/database/factories/files/BieberFever.pdf', true); // add a manuscript file
        });
    }

    /**
     * A manuscript record that has been submitted for internal review
     */
    public function in_review()
    {
        return $this->filled()->state([
            'title' => 'A manuscript record that has been submitted for internal review',
            'status' => ManuscriptRecordStatus::IN_REVIEW,
            'sent_for_review_at' => now(),
        ])->afterCreating(function ($manuscript) {
            $manuscript->lockManuscriptFiles();
        });
    }

    /**
     * A manuscript record that has been reviewed and can be sent for publication
     */
    public function reviewed()
    {
        return $this->in_review()->state([
            'title' => 'A manuscript record that has passed management review',
            'status' => ManuscriptRecordStatus::REVIEWED,
            'reviewed_at' => now(),
        ])->afterCreating(function ($manuscript) {
            $manuscript->managementReviewSteps()->save(\App\Models\ManagementReviewStep::factory()->approved()->make());
        });
    }

    /**
     * A manuscript record that has been withheld by the management review
     */
    public function withheld()
    {
        return $this->in_review()->state([
            'title' => 'A manuscript record that has been withheld by the management review',
            'status' => ManuscriptRecordStatus::WITHHELD,
        ])->afterCreating(function ($manuscript) {
            $manuscript->managementReviewSteps()->save(\App\Models\ManagementReviewStep::factory()->withheld()->make());
        });
    }

    /**
     * A manuscript record that has been accepted for publication
     */
    public function accepted()
    {
        return $this->reviewed()->state([
            'title' => 'A manuscript record that has been accepted for publication',
            'status' => ManuscriptRecordStatus::ACCEPTED,
            'submitted_to_journal_on' => now(),
            'accepted_on' => now(),
        ]);
    }
}
