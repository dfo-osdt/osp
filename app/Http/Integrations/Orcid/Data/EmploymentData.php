<?php

namespace App\Http\Integrations\Orcid\Data;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

/**
 * @property \App\Http\Integrations\Orcid\Data\ExternalIdData[] $externalIds
 */
class EmploymentData extends Data
{
    public function __construct(
        #[MapName('department-name')]
        public string $departmentName,

        #[MapName('role-title')]
        public string $roleTitle,

        #[MapName('start-date')]
        public DateData $startDate,

        #[MapName('end-date')]
        public DateData $endDate,

        public OrganizationData $organization,

        public UrlData $url,

        #[MapName('external-ids.external-id')]
        public array $externalIds

    ) {}
}
