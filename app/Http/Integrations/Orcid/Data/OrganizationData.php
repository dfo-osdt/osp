<?php

namespace App\Http\Integrations\Orcid\Data;

use App\Models\Organization;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

class OrganizationData extends Data
{
    public function __construct(
        #[MapName('name')]
        public string $name,

        #[MapName('address')]
        public AddressData $address,

        #[MapName('disambiguated-organization')]
        public DisambiguatedOrganizationData $disambiguatedOrganization
    ) {}

    public static function fromModel(Organization $organization): self
    {
        return new self(
            name: $organization->name_en,
            address: new AddressData(country: $organization->country_code),
            disambiguatedOrganization: DisambiguatedOrganizationData::from([
                'disambiguatedOrganizationIdentifier' => $organization->ror_identifier,
            ])
        );
    }
}
