<?php

declare(strict_types=1);

namespace App\Orcid\Mappers;

use App\Models\Journal;
use App\Models\PublicationAuthor;
use App\Orcid\Exceptions\WorkNotSyncable;
use App\Services\TitleMarkup;
use InvalidArgumentException;
use VincentAuger\OrcidSdk\Data\Common\Contributor;
use VincentAuger\OrcidSdk\Data\Common\ExternalId;
use VincentAuger\OrcidSdk\Data\Common\FuzzyDate;
use VincentAuger\OrcidSdk\Data\Common\Title;
use VincentAuger\OrcidSdk\Data\Common\Url;
use VincentAuger\OrcidSdk\Data\Works\WorkInput;
use VincentAuger\OrcidSdk\Enums\WorkType;
use VincentAuger\OrcidSdk\Support\OrcidId;

/**
 * Maps an OSP publication author to an ORCID work: the report, identified by its
 * DOI and part of its series' ISSN, with every author of the publication as a contributor. No citation (the
 * DOI metadata does better) and no short description.
 */
class WorkMapper
{
    /**
     * @throws WorkNotSyncable when the publication is not eligible or has data ORCID would reject
     */
    public static function toWorkInput(PublicationAuthor $publicationAuthor): WorkInput
    {
        if (! $publicationAuthor->isEligibleForOrcid()) {
            throw new WorkNotSyncable($publicationAuthor, 'not eligible for ORCID');
        }

        $publication = $publicationAuthor->publication;

        try {
            $doi = ExternalId::doi($publication->doi);

            return new WorkInput(
                title: new Title(TitleMarkup::toPlain($publication->title)),
                type: WorkType::Report,
                externalIds: self::externalIds($doi, $publication->journal),
                journalTitle: $publication->journal->title,
                publicationDate: $publication->published_on === null ? null : FuzzyDate::fromDateTime($publication->published_on),
                url: new Url((string) $doi->url),
                contributors: self::contributors($publicationAuthor),
            );
        } catch (InvalidArgumentException $exception) {
            // data ORCID would reject, e.g. a DOI URL on another resolver or a title longer than ORCID allows
            throw new WorkNotSyncable($publicationAuthor, $exception->getMessage());
        }
    }

    /**
     * The DOI identifies the work; the series' ISSN, when valid, says what it is part
     * of. A missing or malformed ISSN is left out rather than holding back the work.
     *
     * @return list<ExternalId>
     */
    private static function externalIds(ExternalId $doi, Journal $journal): array
    {
        if (! $journal->hasValidIssn()) {
            return [$doi];
        }

        return [$doi, ExternalId::issn(trim((string) $journal->issn))];
    }

    /**
     * Every author of the publication, in OSP order, with their ORCID iD when OSP has
     * one, verified or not: listing an iD never adds the work to that person's record.
     * Group authors are credited with their organization's name. No role or sequence:
     * every contributor is an author, and ORCID shows a dangling "(Author," without a sequence.
     *
     * @return list<Contributor>
     */
    private static function contributors(PublicationAuthor $publicationAuthor): array
    {
        return $publicationAuthor->publication->publicationAuthors()
            ->with('author', 'organization')
            ->orderBy('id')
            ->get()
            ->map(fn (PublicationAuthor $coAuthor): Contributor => new Contributor(
                creditName: $coAuthor->is_group_author ? $coAuthor->organization->name_en : $coAuthor->author->full_name,
                orcid: $coAuthor->is_group_author ? null : self::validOrcid($coAuthor->author->orcid),
            ))
            ->values()
            ->all();
    }

    /**
     * A malformed iD stored for a co-author is left out rather than holding back the work
     */
    private static function validOrcid(?string $orcid): ?string
    {
        return $orcid !== null && OrcidId::isValid($orcid) ? $orcid : null;
    }
}
