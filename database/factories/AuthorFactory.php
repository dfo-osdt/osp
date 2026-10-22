<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Expertise;
use App\Models\Organization;
use App\Models\Region;
use App\Models\Sector;
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
     * An independent researcher without an institutional affiliation
     */
    public function independent()
    {
        return $this->state(fn (array $attributes) => [
            'organization_id' => Organization::getIndependentOrganization()->id,
        ]);
    }

    /**
     * A DFO (default organization) author
     */
    public function internal(): static
    {
        return $this->state(fn (array $attributes) => [
            'organization_id' => Organization::getDefaultOrganization()->id,
        ]);
    }

    /**
     * A DFO author who has filled their sector and region
     */
    public function withCompleteProfile(): static
    {
        return $this->internal()->state(fn (array $attributes) => [
            'sector_id' => Sector::factory(),
            'region_id' => Region::query()->inRandomOrder()->value('id'),
        ]);
    }

    /**
     * Connected to ORCID with a token OSP can write with (a valid iD,
     * the scopes OSP asks for and a long-lived token)
     */
    public function withOrcidConnection(string $scope = '/read-limited /activities/update /person/update'): static
    {
        return $this->state(fn (array $attributes) => [
            'orcid' => 'https://orcid.org/0000-0002-1825-0097',
            'orcid_verified' => true,
            'orcid_access_token' => 'orcid-test-access-token',
            'orcid_refresh_token' => 'orcid-test-refresh-token',
            'orcid_token_scope' => $scope,
            'orcid_expires_at' => now()->addYears(20),
        ]);
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
