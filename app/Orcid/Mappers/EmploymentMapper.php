<?php

declare(strict_types=1);

namespace App\Orcid\Mappers;

use App\Models\AuthorEmployment;
use App\Orcid\Exceptions\EmploymentNotSyncable;
use VincentAuger\OrcidSdk\Data\Affiliations\AffiliationInput;
use VincentAuger\OrcidSdk\Data\Common\FuzzyDate;
use VincentAuger\OrcidSdk\Data\Organization\DisambiguatedOrganization;
use VincentAuger\OrcidSdk\Data\Organization\Organization;
use VincentAuger\OrcidSdk\Data\Organization\OrganizationAddress;

/**
 * Maps an OSP employment to an ORCID employment affiliation.
 */
class EmploymentMapper
{
    /**
     * OSP organizations have no city yet; ORCID requires one for employments.
     */
    public const string DEFAULT_CITY = 'Ottawa';

    /**
     * @throws EmploymentNotSyncable when the organization is missing data ORCID requires
     */
    public static function toAffiliationInput(AuthorEmployment $authorEmployment): AffiliationInput
    {
        $organization = $authorEmployment->organization;

        if ($organization === null) {
            throw new EmploymentNotSyncable($authorEmployment, 'no organization');
        }

        if (blank($organization->country_code)) {
            throw new EmploymentNotSyncable($authorEmployment, "organization {$organization->id} has no country");
        }

        if (blank($organization->ror_identifier)) {
            throw new EmploymentNotSyncable($authorEmployment, "organization {$organization->id} has no ROR identifier");
        }

        return new AffiliationInput(
            organization: new Organization(
                name: $organization->name_en,
                address: new OrganizationAddress(city: self::DEFAULT_CITY, country: $organization->country_code),
                disambiguatedOrganization: DisambiguatedOrganization::ror($organization->ror_identifier),
            ),
            departmentName: $authorEmployment->department_name,
            roleTitle: $authorEmployment->role_title,
            startDate: FuzzyDate::fromDateTime($authorEmployment->start_date),
            endDate: $authorEmployment->end_date ? FuzzyDate::fromDateTime($authorEmployment->end_date) : null,
        );
    }
}
