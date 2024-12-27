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
        $email = $fistName.'.'.$lastName.'@'.$this->faker->domainWord().'.dev';
        $hasOrcid = $this->faker->boolean(70);
        $orcid = $hasOrcid ? 'https://sandbox.orcid.org/'.$this->faker->numerify('####-####-####-####') : null;

        return [
            'first_name' => $fistName,
            'last_name' => $lastName,
            'email' => $email,
            'orcid' => $orcid,
            'organization_id' => \App\Models\Organization::factory(),
        ];
    }

    /**
     * A factory for an author with a user account
     */
    public function isFromAuthorizedDomain()
    {
        $domains = config('osp.allowed_registration_email_domains');
        $domain = $domains[0];

        return $this->state(function (array $attributes) use ($domain) {
            return [
                'email' => $attributes['first_name'].'.'.$attributes['last_name'].'@'.$domain,
                'organization_id' => 1,
            ];
        });
    }

    /**
     * Has expertise
     */
    public function hasExpertises(int $qty = 1)
    {
        return $this->afterCreating(function (\App\Models\Author $author) use ($qty) {
            $expertise = \App\Models\Expertise::factory()->count($qty)->create();
            $author->expertises()->attach($expertise);
        });
    }
}
