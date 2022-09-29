<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Author>
 */
class AuthorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $fistName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        $email = $fistName.'.'.$lastName.'@'.$this->faker->safeEmailDomain;

        return [
            'first_name' => $fistName,
            'last_name' => $lastName,
            'email' => $email,
            'orcid' => $this->faker->unique()->optional()->numerify('####-####-####-####'),
            'organization_id' => \App\Models\Organization::factory(),
        ];
    }
}
