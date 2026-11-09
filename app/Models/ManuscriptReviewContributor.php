<?php

namespace App\Models;

use App\Contracts\HasOrcidSyncStatus;
use App\Enums\ManuscriptRecordType;
use App\Enums\OrcidSyncIntent;
use App\Enums\OrcidSyncStatus;
use App\Enums\PublicationStatus;
use Database\Factories\ManuscriptReviewContributorFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use VincentAuger\OrcidSdk\Enums\PeerReviewRole;
use VincentAuger\OrcidSdk\Enums\PeerReviewType;

/**
 * Someone who reviewed or edited a secondary manuscript, with their
 * ORCID peer review role so they can later claim credit for it.
 *
 * @property PeerReviewType $type
 * @property PeerReviewRole $role
 * @property OrcidSyncIntent|null $orcid_sync
 * @property Carbon|null $orcid_sync_at When the author last decided on orcid_sync
 * @property int|null $orcid_putcode
 * @property Carbon|null $orcid_updated_at
 * @property Carbon|null $deleted_at
 * @property-read Author|null $author
 * @property-read ManuscriptRecord|null $manuscriptRecord
 *
 * @method static \Database\Factories\ManuscriptReviewContributorFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptReviewContributor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptReviewContributor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptReviewContributor query()
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $manuscript_record_id
 * @property int $author_id
 * @property Carbon|null $review_date
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptReviewContributor whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptReviewContributor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptReviewContributor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptReviewContributor whereManuscriptRecordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptReviewContributor whereReviewDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptReviewContributor whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptReviewContributor whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptReviewContributor whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
#[Fillable([
    'manuscript_record_id',
    'author_id',
    'role',
    'review_date',
])]
class ManuscriptReviewContributor extends Model implements HasOrcidSyncStatus
{
    /** @use HasFactory<ManuscriptReviewContributorFactory> */
    use HasFactory;

    use SoftDeletes;

    #[\Override]
    protected $attributes = [
        'role' => PeerReviewRole::Reviewer,
        'type' => PeerReviewType::Review,
    ];

    public function casts(): array
    {
        return [
            'review_date' => 'date',
            'type' => PeerReviewType::class,
            'role' => PeerReviewRole::class,
            'orcid_sync' => OrcidSyncIntent::class,
            'orcid_sync_at' => 'datetime',
            'orcid_updated_at' => 'datetime',
        ];
    }

    public function isWantedOnOrcid(): bool
    {
        return $this->orcid_sync === OrcidSyncIntent::WANTED;
    }

    /**
     * Record whether the author wants the contribution on ORCID, logged on the
     * manuscript. The causer is the user whose action decided it: the author,
     * or whoever published it when the author's auto sync decided.
     */
    public function recordOrcidIntent(OrcidSyncIntent $intent, User $causer, bool $byAutoSync = false): void
    {
        $this->orcid_sync = $intent;
        $this->orcid_sync_at = now();
        $this->save();

        activity()
            ->performedOn($this->manuscriptRecord)
            ->withProperties([
                'author_id' => $this->author_id,
                'orcid_sync' => $intent->value,
                ...($byAutoSync ? ['auto_sync' => true] : []),
            ])
            ->causedBy($causer)
            ->log(match ($intent) {
                OrcidSyncIntent::WANTED => 'manuscript.review-contributor.orcid-wanted',
                OrcidSyncIntent::DECLINED => 'manuscript.review-contributor.orcid-declined',
            });
    }

    public function isSyncedWithOrcid(): bool
    {
        return $this->orcid_putcode !== null;
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
     * Whether the contribution is on the author's ORCID record but should no longer
     * be: removed from the manuscript, declined by the author or no longer eligible.
     */
    public function needsRemovalFromOrcid(): bool
    {
        return $this->isSyncedWithOrcid()
            && ($this->trashed() || ! $this->isWantedOnOrcid() || ! $this->isEligibleForOrcid());
    }

    /**
     * Where the contribution stands with ORCID, for auditing. What is on ORCID
     * and up to date shows as such even if the author has since disconnected.
     *
     * Eager load `author` and `manuscriptRecord.publication.journal` when checking many.
     */
    public function orcidSyncStatus(): OrcidSyncStatus
    {
        return match (true) {
            $this->needsRemovalFromOrcid() => OrcidSyncStatus::REMOVING,
            ! $this->isEligibleForOrcid() || $this->author?->user_id === null => OrcidSyncStatus::NOT_ELIGIBLE,
            $this->orcid_sync === OrcidSyncIntent::DECLINED => OrcidSyncStatus::DECLINED,
            $this->isSyncedWithOrcid() && ! $this->needsSyncWithOrcid() => OrcidSyncStatus::ON_ORCID,
            ! $this->author->hasValidOrcidCredentials() => OrcidSyncStatus::NOT_CONNECTED,
            $this->orcid_sync === null => OrcidSyncStatus::NOT_DECIDED,
            default => OrcidSyncStatus::PENDING,
        };
    }

    /**
     * The ORCID peer review roles that apply to our reports. Chair,
     * member and organizer are for conferences and committees.
     *
     * @return array<int, PeerReviewRole>
     */
    public static function allowedRoles(): array
    {
        return [
            PeerReviewRole::Reviewer,
            PeerReviewRole::Editor,
        ];
    }

    /**
     * Whether this contribution can be on the author's ORCID record right now:
     * a secondary manuscript whose publication is published with a DOI, in a
     * DFO series flagged for ORCID sync with a valid ISSN (the review group id).
     *
     * Eager load `manuscriptRecord.publication.journal` when checking many.
     */
    public function isEligibleForOrcid(): bool
    {
        return $this->orcidIneligibilityReason() === null;
    }

    /**
     * Why this contribution cannot be on the author's ORCID record right now,
     * as a key of `orcid.ineligible`, or null when it is eligible.
     *
     * Eager load `manuscriptRecord.publication.journal` when checking many.
     */
    public function orcidIneligibilityReason(): ?string
    {
        $manuscriptRecord = $this->manuscriptRecord;

        if ($manuscriptRecord === null) {
            return 'manuscript_deleted';
        }
        if ($manuscriptRecord->type !== ManuscriptRecordType::SECONDARY) {
            return 'not_secondary';
        }

        $publication = $manuscriptRecord->publication;

        if ($publication === null) {
            return 'no_publication';
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
        if (! $publication->journal->hasValidIssn()) {
            return 'invalid_issn';
        }

        return null;
    }

    /**
     * @return BelongsTo<ManuscriptRecord, $this>
     */
    public function manuscriptRecord(): BelongsTo
    {
        return $this->belongsTo(ManuscriptRecord::class);
    }

    /**
     * @return BelongsTo<Author, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
}
