<?php

declare(strict_types=1);

namespace App\Orcid;

use App\Models\Author;
use App\Models\AuthorEmployment;
use App\Models\Organization;
use App\Orcid\Exceptions\AuthorNotConnectedToOrcid;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;
use VincentAuger\OrcidSdk\Data\Affiliations\Affiliation;
use VincentAuger\OrcidSdk\Data\Common\FuzzyDate;
use VincentAuger\OrcidSdk\Enums\AffiliationType;
use VincentAuger\OrcidSdk\Exceptions\OrcidException;

/**
 * Brings an author's OSP employments in line with the employments OSP created
 * on their ORCID record (the pull side; SyncAuthorEmploymentWithOrcid pushes):
 *
 * - items OSP created that OSP no longer has (e.g. after a database restore or
 *   when the author registered again) are imported, so OSP can update or delete
 *   them again instead of creating duplicates
 * - employments whose item the author deleted on ORCID are deleted in OSP
 *
 * Employments with changes not yet pushed to ORCID, and employments deleted in
 * OSP and waiting to be removed from ORCID, are left to the push job.
 */
class EmploymentReconciler
{
    /**
     * Changes pushed within this many seconds before ORCID was read may not be
     * in what ORCID returned: leave them for the next reconcile.
     */
    private const int RECENT_SECONDS = 300;

    public function __construct(private readonly OrcidClientFactory $orcid) {}

    /**
     * @return array{imported: int, deleted: int}
     *
     * @throws AuthorNotConnectedToOrcid
     * @throws OrcidException
     */
    public function reconcile(Author $author): array
    {
        $readAt = now();
        $items = array_filter(
            $this->orcid->forAuthor($author)->affiliations()->list(AffiliationType::Employment),
            fn (Affiliation $item): bool => (bool) $item->source?->isClient((string) $this->orcid->clientId),
        );
        $recent = $readAt->copy()->subSeconds(self::RECENT_SECONDS);

        $employments = $author->employments()->withTrashed()->whereNotNull('orcid_putcode')->get();
        $knownPutCodes = $employments->pluck('orcid_putcode')->all();
        $putCodesOnOrcid = array_map(fn (Affiliation $item): int => $item->putCode, $items);

        $deleted = $employments
            ->filter(fn (AuthorEmployment $employment): bool => ! $employment->trashed()
                && ! in_array($employment->orcid_putcode, $putCodesOnOrcid, true)
                && ! $employment->needsSyncWithOrcid()
                && $employment->orcid_updated_at->lt($recent))
            ->each(function (AuthorEmployment $employment): void {
                $employment->forceDelete();

                activity()
                    ->performedOn($employment)
                    ->event('deleted')
                    ->withProperties(['source' => 'orcid', 'orcid_putcode' => $employment->orcid_putcode])
                    ->log('Employment deleted because the author deleted it from their ORCID record');

                Log::info('ORCID employment deleted by the author, deleted in OSP', ['author_employment_id' => $employment->id, 'put_code' => $employment->orcid_putcode]);
            })
            ->count();

        $organization = Organization::getDefaultOrganization();
        $imported = 0;

        foreach ($items as $item) {
            // a push job may not have stored the put-code of an item it just created
            if (in_array($item->putCode, $knownPutCodes, true) || ($item->createdAt !== null && Date::instance($item->createdAt)->gt($recent))) {
                continue;
            }

            if (! $this->isWithOrganization($item, $organization) || ! $item->startDate instanceof FuzzyDate) {
                Log::warning('ORCID employment created by OSP cannot be imported', ['author_id' => $author->id, 'put_code' => $item->putCode]);

                continue;
            }

            $this->import($author, $item, $item->startDate, $organization);
            $imported++;
        }

        return ['imported' => $imported, 'deleted' => $deleted];
    }

    private function import(Author $author, Affiliation $item, FuzzyDate $startDate, Organization $organization): void
    {
        $now = now();

        $employment = new AuthorEmployment([
            'author_id' => $author->id,
            'organization_id' => $organization->id,
            'role_title' => $item->roleTitle,
            'department_name' => $item->departmentName,
            'start_date' => $this->toDate($startDate),
            'end_date' => $item->endDate instanceof FuzzyDate ? $this->toDate($item->endDate) : null,
        ]);

        // what is on ORCID is exactly what OSP sent: the employment is in sync
        $employment->forceFill([
            'orcid_putcode' => $item->putCode,
            'orcid_updated_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ])->save();

        activity()
            ->performedOn($employment)
            ->event('created')
            ->withProperties(['source' => 'orcid', 'orcid_putcode' => $item->putCode])
            ->log('Employment imported from the author\'s ORCID record, where OSP had created it');

        Log::info('ORCID employment imported', ['author_employment_id' => $employment->id, 'put_code' => $item->putCode]);
    }

    /**
     * OSP only creates employments with the default organization (DFO)
     */
    private function isWithOrganization(Affiliation $item, Organization $organization): bool
    {
        $identifier = $item->organization?->disambiguatedOrganization?->identifier;

        return $identifier !== null && filled($organization->ror_identifier)
            && strcasecmp(rtrim($identifier, '/'), rtrim((string) $organization->ror_identifier, '/')) === 0;
    }

    /**
     * OSP always sends full dates; a partial date defaults to the first month or day
     */
    private function toDate(FuzzyDate $date): Carbon
    {
        return Date::create($date->year, $date->month ?? 1, $date->day ?? 1);
    }
}
