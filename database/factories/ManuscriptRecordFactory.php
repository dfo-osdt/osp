<?php

namespace Database\Factories;

use App\Enums\ManuscriptRecordStatus;
use App\Enums\ManuscriptRecordType;
use App\Models\FunctionalArea;
use App\Models\FundingSource;
use App\Models\ManuscriptAuthor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ManuscriptRecord>
 */
class ManuscriptRecordFactory extends Factory
{
    /**
     * Define the model's default state.
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
     * Secondary manuscript record
     */
    public function secondary()
    {
        return $this->state([
            'type' => ManuscriptRecordType::SECONDARY,
        ]);
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
            'public_interest_information' => $this->faker->paragraph(),
            'functional_area_id' => FunctionalArea::factory()->create()->id,
        ])->afterCreating(function ($manuscript) {
            $manuscript->manuscriptAuthors()->save(ManuscriptAuthor::factory()->make(['is_corresponding_author' => true])); // create a corresponding author
            $manuscript->manuscriptAuthors()->saveMany(ManuscriptAuthor::factory()->count(3)->make()); // create 3 other authors
            $manuscript->fundingSources()->saveMany(FundingSource::factory()->count(3)->make()); // create 3 funding sources
            $manuscript->addManuscriptFile(getcwd().'/database/factories/files/BieberFever.pdf', true); // add a manuscript file
            if ($manuscript->type == ManuscriptRecordType::SECONDARY) {
                $manuscript->peerReviewers()->saveMany(\App\Models\ManuscriptPeerReviewer::factory()->count(2)->make()); // add 2 peer reviewers for secondary manuscripts
            }
        });
    }

    /**
     * A manuscript record that has been submitted for internal review
     */
    public function in_review(bool $withAreviewstep = true, bool $secondary = false)
    {
        return $this->filled()->state([
            'title' => 'A manuscript record that has been submitted for internal review',
            'status' => ManuscriptRecordStatus::IN_REVIEW,
            'sent_for_review_at' => now(),
        ])->afterCreating(function ($manuscript) use ($withAreviewstep, $secondary) {
            $manuscript->lockManuscriptFiles();
            if ($withAreviewstep) {
                $step = $manuscript->managementReviewSteps()->save(\App\Models\ManagementReviewStep::factory()->make());
                if ($secondary) {
                    $step->decision_expected_by = null;
                    $step->save();
                }
            }
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
            $manuscript->managementReviewSteps()->update([
                'status' => \App\Enums\ManagementReviewStepStatus::COMPLETED,
                'decision' => \App\Enums\ManagementReviewStepDecision::COMPLETE,
                'comments' => 'This manuscript is approved - great job!',
                'completed_at' => now(),
            ]);
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

    /**
     * A published preprint - accepted with url.
     */
    public function publishedPreprint()
    {
        return $this->accepted()->state([
            'title' => 'A preprint' . $this->faker->text(50),
            'submitted_to_journal_on' => now(),
            'accepted_on' => now(),
            'type' => ManuscriptRecordType::PREPRINT,
            'preprint_url' => $this->faker->url()
        ]);
    }
}
