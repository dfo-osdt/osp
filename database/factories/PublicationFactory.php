<?php

namespace Database\Factories;

use App\Enums\PublicationStatus;
use App\Enums\SupplementaryFileType;
use App\Models\PublicationAuthor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Publication>
 */
class PublicationFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'doi' => $this->getDOI(),
            'journal_id' => \App\Models\Journal::factory(),
            'accepted_on' => $this->faker->date(),
            'user_id' => \App\Models\User::factory(),
            'region_id' => rand(1, 5),
        ];
    }

    public function dfoSeries()
    {
        return $this->state(function (array $attributes) {
            return [
                'journal_id' => \App\Models\Journal::factory()->dfoSeries(),
            ];
        });
    }

    /**
     * Indicate that the publication is open access.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Publication>
     */
    public function openAccess()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_open_access' => true,
            ];
        });
    }

    /** Published publication */
    public function published()
    {
        return $this->state(function (array $attributes) {
            $acceptedOn = $attributes['accepted_on'] ?? Carbon::instance($this->faker->dateTimeBetween('-1 year', 'now'));
            $publishedOn = Carbon::parse($acceptedOn)->addDays($this->faker->numberBetween(15, 30));

            return [
                'status' => PublicationStatus::PUBLISHED,
                'accepted_on' => $acceptedOn,
                'published_on' => $publishedOn,
            ];
        })->afterCreating(function ($publication) {
            $publication->addPublicationFile(getcwd().'/database/factories/files/BieberFever.pdf', true);
            $publication->addSupplementaryFile(getcwd().'/database/factories/files/BieberFever.pdf', SupplementaryFileType::AUTHOR_AGREEMENT, 'Supplementary file', true);
        });
    }

    /** Has a manuscript */
    public function withManuscript()
    {
        return $this->state(function (array $attributes) {
            return [
                'manuscript_record_id' => \App\Models\ManuscriptRecord::factory()->accepted(),
            ];
        });
    }

    /** has authors */
    public function withAuthors()
    {
        return $this->afterCreating(
            function (\App\Models\Publication $publication) {
                $publication->publicationAuthors()->saveMany(PublicationAuthor::factory()->count(3)->make());
            }
        );
    }

    public function getDOI(): string
    {
        // DOI regular expression
        $doiRegex = '/^10\.\d{4,9}\/[-._;()\/:A-Z0-9]+$/i';

        return $this->faker->regexify($doiRegex);
    }
}
