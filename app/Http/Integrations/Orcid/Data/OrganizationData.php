<?php

namespace App\Http\Integrations\Orcid\Data;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

class OrganizationData extends Data{

    public function __construct(
        #[MapName('name')]
        public string $name,

        #[MapName('address')]
        public AddressData $address,

        #[MapName('disambiguated-organization')]
        public DisambiguatedOrganizationData $disambiguatedOrganization
    ) {}

}