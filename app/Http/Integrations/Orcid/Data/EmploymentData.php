<?php

namespace App\Http\Integrations\Orcid\Data;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class EmploymentData extends Data
{
    public function __construct(
        #[MapName('department-name')]
        public string|Optional $departmentName,

        #[MapName('role-title')]
        public string|Optional $roleTitle,

        #[MapName('start-date')]
        public DateData|Optional $startDate,

        #[MapName('end-date')]
        public DateData|Optional $endDate,

        public OrganizationData $organization,

        public UrlData|Optional $url,

        #[MapName('external-ids')]
        public ExternalIdsData $externalIds

    ) {}
}
