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
        $fistName = $this->faker->firstName();
        $lastName = $this->faker->lastName();
        $email = $fistName.'.'.$lastName.'@'.$this->faker->safeEmailDomain();

        return [
            'first_name' => $fistName,
            'last_name' => $lastName,
            'email' => $email,
            'orcid' => $this->faker->optional()->numerify('####-####-####-####'),
            'organization_id' => \App\Models\Organization::factory(),
        ];
    }

    /**
     * A factory for an author with a user account
     */
    public function isFromDFO()
    {
        return $this->state(function (array $attributes) {
            return [
                'user_id' => \App\Models\User::factory(),
                'email' => $attributes['first_name'].'.'.$attributes['last_name'].'@dfo-mpo.gc.ca',
                'organization_id' => 1,
            ];
        });
    }
}
