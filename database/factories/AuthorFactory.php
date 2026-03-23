<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Expertise;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Author>
 */
class AuthorFactory extends Factory
{
    /**
     * Define the model's default state.
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
            'organization_id' => Organization::factory(),
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
        return $this->afterCreating(function (Author $author) use ($qty) {
            $expertise = Expertise::factory()->count($qty)->create();
            $author->expertises()->attach($expertise);
        });
    }
}
