<?php

declare(strict_types=1);

namespace App\Orcid\Mappers;

use App\Models\ManuscriptReviewContributor;
use App\Models\Organization;
use App\Orcid\Exceptions\ReviewContributionNotSyncable;
use InvalidArgumentException;
use VincentAuger\OrcidSdk\Data\Common\ExternalId;
use VincentAuger\OrcidSdk\Data\Common\FuzzyDate;
use VincentAuger\OrcidSdk\Data\Common\Title;
use VincentAuger\OrcidSdk\Data\Common\Url;
use VincentAuger\OrcidSdk\Data\Organization\DisambiguatedOrganization;
use VincentAuger\OrcidSdk\Data\Organization\Organization as OrcidOrganization;
use VincentAuger\OrcidSdk\Data\Organization\OrganizationAddress;
use VincentAuger\OrcidSdk\Data\PeerReviews\PeerReviewInput;
use VincentAuger\OrcidSdk\Enums\PeerReviewSubjectType;

/**
 * Maps an OSP review contribution to an ORCID peer review: the contributor
 * reviewed or edited a report, convened by the default organization (DFO) and
 * grouped under the series' ISSN.
 */
class PeerReviewMapper
{
    /**
     * @throws ReviewContributionNotSyncable when the contribution or the default organization has data ORCID would reject
     */
    public static function toPeerReviewInput(ManuscriptReviewContributor $contributor): PeerReviewInput
    {
        if (! $contributor->isEligibleForOrcid()) {
            throw new ReviewContributionNotSyncable($contributor, 'not eligible for ORCID');
        }

        $organization = Organization::getDefaultOrganization();

        if (blank($organization->ror_identifier)) {
            throw new ReviewContributionNotSyncable($contributor, "organization {$organization->id} has no ROR identifier");
        }

        $publication = $contributor->manuscriptRecord->publication;

        try {
            $doi = ExternalId::doi($publication->doi);

            return new PeerReviewInput(
                role: $contributor->role,
                type: $contributor->type,
                reviewIdentifiers: [ExternalId::sourceWorkId(self::sourceWorkId($contributor))],
                completionDate: FuzzyDate::fromDateTime($contributor->review_date ?? $contributor->created_at),
                reviewGroupId: 'issn:'.trim((string) $publication->journal->issn),
                conveningOrganization: new OrcidOrganization(
                    name: $organization->name_en,
                    address: new OrganizationAddress(city: EmploymentMapper::DEFAULT_CITY, country: $organization->country_code),
                    disambiguatedOrganization: DisambiguatedOrganization::ror($organization->ror_identifier),
                ),
                subjectExternalId: $doi,
                subjectContainerName: $publication->journal->title,
                subjectType: PeerReviewSubjectType::Report,
                subjectName: new Title($publication->title),
                subjectUrl: new Url((string) $doi->url),
            );
        } catch (InvalidArgumentException $exception) {
            // data ORCID would reject, e.g. a DOI URL on another resolver or a title longer than ORCID allows
            throw new ReviewContributionNotSyncable($contributor, $exception->getMessage());
        }
    }

    /**
     * The review's own identifier: the manuscript record's ULID and the contributor id,
     * e.g. "01JC3Z8Q4M5N6P7R8S9T0V1W2X-42". It stays unique across OSP environments.
     */
    private static function sourceWorkId(ManuscriptReviewContributor $contributor): string
    {
        return "{$contributor->manuscriptRecord->ulid}-{$contributor->id}";
    }
}
