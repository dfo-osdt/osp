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
     */
    public function definition()
    {
        return [
            'is_validated' => true,
            'name_en' => $this->faker->unique()->sentence(4),
            'name_fr' => $this->faker->unique()->sentence(4),
            'abbr_en' => Str::upper($this->faker->word()),
            'abbr_fr' => Str::upper($this->faker->word()),
            'ror_identifier' => $this->fakeRORIdentifier(),
            'country_code' => $this->faker->randomElement(['CA', 'US', 'FR', 'UK']),
        ];
    }

    private function fakeRORIdentifier(): string
    {
        $base_url = 'https://ror.org/';
        $ror_regex = '0[0-9a-z]{6}[0-9]';
        $ror_id = $this->faker->regexify($ror_regex);

        return $base_url.$ror_id;
    }
}
