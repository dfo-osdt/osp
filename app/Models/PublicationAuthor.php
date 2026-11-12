<?php

namespace App\Models;

use App\Contracts\HasOrcidSyncStatus;
use App\Enums\OrcidSyncIntent;
use App\Enums\OrcidSyncStatus;
use App\Enums\PublicationStatus;
use Illuminate\Database\Eloquent\Attributes\Touches;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $publication_id
 * @property int $author_id
 * @property int $organization_id
 * @property int|null $sector_id
 * @property int|null $region_id
 * @property bool|null $is_emeritus
 * @property-read Sector|null $sector
 * @property-read Region|null $region
 * @property bool $is_corresponding_author
 * @property bool $is_group_author
 * @property-read Collection<int, Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @property-read Author $author
 * @property-read Organization $organization
 * @property-read Publication|null $publication Null when the publication was deleted
 * @property OrcidSyncIntent|null $orcid_sync
 * @property Carbon|null $orcid_sync_at When the author last decided on orcid_sync
 * @property int|null $orcid_putcode
 * @property Carbon|null $orcid_updated_at
 *
 * @method static \Database\Factories\PublicationAuthorFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicationAuthor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicationAuthor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicationAuthor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicationAuthor whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicationAuthor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicationAuthor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicationAuthor whereIsCorrespondingAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicationAuthor whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicationAuthor wherePublicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicationAuthor whereUpdatedAt($value)
 *
 * @property Carbon|null $deleted_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicationAuthor onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicationAuthor whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicationAuthor withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicationAuthor withoutTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PublicationAuthor whereIsGroupAuthor($value)
 *
 * @mixin \Eloquent
 */
#[Touches(['publication'])]
class PublicationAuthor extends Model implements HasOrcidSyncStatus
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    /**
     * What OSP records about the author's ORCID work. Intent changes and ORCID
     * writes are logged on the publication instead (see recordOrcidIntent()).
     */
    private const array ORCID_ATTRIBUTES = ['orcid_sync', 'orcid_sync_at', 'orcid_putcode', 'orcid_updated_at'];

    #[\Override]
    public $guarded = [
        'id',
        'created_at',
        'updated_at',
        ...self::ORCID_ATTRIBUTES,
    ];

    public function casts(): array
    {
        return [
            'is_corresponding_author' => 'boolean',
            'is_group_author' => 'boolean',
            'is_emeritus' => 'boolean',
            'orcid_sync' => OrcidSyncIntent::class,
            'orcid_sync_at' => 'datetime',
            'orcid_updated_at' => 'datetime',
        ];
    }

    // logging options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logExcept(self::ORCID_ATTRIBUTES)
            ->dontLogIfAttributesChangedOnly([...self::ORCID_ATTRIBUTES, 'updated_at'])
            ->logOnlyDirty();
    }

    public function isWantedOnOrcid(): bool
    {
        return $this->orcid_sync === OrcidSyncIntent::WANTED;
    }

    /**
     * Record whether the author wants the publication on ORCID, logged on the
     * publication. The causer is the user whose action decided it: the author,
     * or whoever published it when the author's auto sync decided.
     */
    public function recordOrcidIntent(OrcidSyncIntent $intent, User $causer, bool $byAutoSync = false): void
    {
        $this->orcid_sync = $intent;
        $this->orcid_sync_at = now();
        $this->save();

        activity()
            ->performedOn($this->publication)
            ->withProperties([
                'author_id' => $this->author_id,
                'orcid_sync' => $intent->value,
                ...($byAutoSync ? ['auto_sync' => true] : []),
            ])
            ->causedBy($causer)
            ->log(match ($intent) {
                OrcidSyncIntent::WANTED => 'publication.author.orcid-wanted',
                OrcidSyncIntent::DECLINED => 'publication.author.orcid-declined',
            });
    }

    public function isSyncedWithOrcid(): bool
    {
        return $this->orcid_putcode !== null;
    }

    /**
     * Whether the author was taken off the publication, as opposed to the row
     * being trashed along with its deleted publication (which can be restored).
     */
    public function isRemovedFromPublication(): bool
    {
        // the relation leaves out deleted publications
        return $this->trashed() && $this->publication !== null;
    }

    /**
     * Whether ORCID should be (re)written: wanted, eligible and changed since it was last synced
     */
    public function needsSyncWithOrcid(): bool
    {
        return ! $this->trashed()
            && $this->isWantedOnOrcid()
            && $this->isEligibleForOrcid()
            && ($this->orcid_updated_at === null || $this->updated_at->gt($this->orcid_updated_at));
    }

    /**
     * Whether the work is on the author's ORCID record but should no longer be:
     * the author was removed, declined it, or it is no longer eligible (including
     * when the publication was deleted).
     */
    public function needsRemovalFromOrcid(): bool
    {
        return $this->isSyncedWithOrcid()
            && ($this->trashed() || ! $this->isWantedOnOrcid() || ! $this->isEligibleForOrcid());
    }

    /**
     * Where the work stands with ORCID, for auditing. What is on ORCID and up
     * to date shows as such even if the author has since disconnected.
     *
     * Eager load `author` and `publication.journal` when checking many.
     */
    public function orcidSyncStatus(): OrcidSyncStatus
    {
        return match (true) {
            $this->needsRemovalFromOrcid() => OrcidSyncStatus::REMOVING,
            ! $this->isEligibleForOrcid() || $this->author->user_id === null => OrcidSyncStatus::NOT_ELIGIBLE,
            $this->orcid_sync === OrcidSyncIntent::DECLINED => OrcidSyncStatus::DECLINED,
            $this->isSyncedWithOrcid() && ! $this->needsSyncWithOrcid() => OrcidSyncStatus::ON_ORCID,
            ! $this->author->hasValidOrcidCredentials() => OrcidSyncStatus::NOT_CONNECTED,
            $this->orcid_sync === null => OrcidSyncStatus::NOT_DECIDED,
            default => OrcidSyncStatus::PENDING,
        };
    }

    /**
     * Whether the publication can be on the author's ORCID record as a work right
     * now: published with a DOI, in a DFO series flagged for ORCID sync.
     *
     * Eager load `publication.journal` when checking many.
     */
    public function isEligibleForOrcid(): bool
    {
        return $this->orcidIneligibilityReason() === null;
    }

    /**
     * Why the publication cannot be on the author's ORCID record right now, as
     * a key of `orcid.ineligible`, or null when it is eligible.
     *
     * Eager load `publication.journal` when checking many.
     */
    public function orcidIneligibilityReason(): ?string
    {
        $publication = $this->publication;

        if ($publication === null) {
            return 'publication_deleted';
        }
        if ($publication->status !== PublicationStatus::PUBLISHED) {
            return 'not_published';
        }
        if (blank($publication->doi)) {
            return 'no_doi';
        }
        if (! $publication->journal->isOrcidSyncEnabled()) {
            return 'journal_not_synced';
        }

        return null;
    }

    // Relationships
    /**
     * A publication author belongs to a publication.
     *
     * @return BelongsTo<Publication, $this>
     */
    public function publication(): BelongsTo
    {
        return $this->belongsTo(Publication::class);
    }

    /** Organization
     * @return BelongsTo<Organization, $this> */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /** Sector
     * @return BelongsTo<Sector, $this> */
    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }

    /** Region
     * @return BelongsTo<Region, $this> */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    /** Author
     * @return BelongsTo<Author, $this> */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * Was the author an independent researcher (no affiliation) for this record?
     */
    public function isIndependent(): bool
    {
        return $this->organization_id === Organization::getIndependentOrganization()->id;
    }
}
