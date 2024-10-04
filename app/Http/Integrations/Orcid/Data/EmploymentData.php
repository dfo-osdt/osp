<?php

namespace App\Http\Integrations\Orcid\Data;

use App\Models\AuthorEmployment;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class EmploymentData extends Data
{
    public function __construct(

        #[MapName('created-date')]
        public TimestampData|Optional $createdDate,

        #[MapName('last-modified-date')]
        public TimestampData|Optional $lastModifiedDate,

        #[MapName('put-code')]
        public int|Optional $putCode,

        #[MapName('department-name')]
        public string|null|Optional $departmentName,

        #[MapName('role-title')]
        public string|null|Optional $roleTitle,

        #[MapName('start-date')]
        public DateData|null|Optional $startDate,

        #[MapName('end-date')]
        public DateData|null|Optional $endDate,

        public OrganizationData $organization,

        public UrlData|null|Optional $url,

        #[MapName('external-ids')]
        public ?ExternalIdsData $externalIds

    ) {}

    public static function fromModel(AuthorEmployment $authorEmployment)
    {
        return new self(
            createdDate: Optional::create(),
            lastModifiedDate: Optional::create(),
            putCode: $authorEmployment->orcid_putcode ? $authorEmployment->orcid_putcode : Optional::create(),
            departmentName: $authorEmployment->department_name,
            roleTitle: $authorEmployment->role_title,
            startDate: $authorEmployment->start_date ? DateData::from($authorEmployment->start_date->format('Y-m-d')) : null,
            endDate: $authorEmployment->end_date ? DateData::from($authorEmployment->end_date->format('Y-m-d')) : null,
            organization: OrganizationData::fromModel($authorEmployment->organization),
            url: Optional::create(),
            externalIds: null
        );
    }
}
