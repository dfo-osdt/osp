<?php

namespace App\Models;

use App\Enums\ManuscriptRecordType;
use App\Enums\OrcidSyncIntent;
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
class ManuscriptReviewContributor extends Model
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
            'orcid_updated_at' => 'datetime',
        ];
    }

    public function isWantedOnOrcid(): bool
    {
        return $this->orcid_sync === OrcidSyncIntent::WANTED;
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
        $manuscriptRecord = $this->manuscriptRecord;
        $publication = $manuscriptRecord?->publication;
        $journal = $publication?->journal;

        if ($manuscriptRecord?->type !== ManuscriptRecordType::SECONDARY || $journal === null) {
            return false;
        }

        return $publication->status === PublicationStatus::PUBLISHED
            && filled($publication->doi)
            && $journal->isOrcidSyncEnabled()
            && $journal->hasValidIssn();
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
