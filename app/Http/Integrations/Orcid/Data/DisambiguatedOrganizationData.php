<?php

declare(strict_types=1);

namespace App\Http\Integrations\Orcid\Data;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

class DisambiguatedOrganizationData extends Data
{
    public function __construct(
        #[MapName('disambiguated-organization-identifier')]
        public string $disambiguatedOrganizationIdentifier,
        #[MapName('disambiguation-source')]
        public string $disambiguationSource = 'ROR'
    ) {}
}
