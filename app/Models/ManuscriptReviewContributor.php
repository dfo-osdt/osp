<?php

namespace App\Models;

use Database\Factories\ManuscriptReviewContributorFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use VincentAuger\OrcidSdk\Enums\PeerReviewRole;
use VincentAuger\OrcidSdk\Enums\PeerReviewType;

/**
 * Someone who reviewed or edited a secondary manuscript, with their
 * ORCID peer review role so they can later claim credit for it.
 *
 * @property PeerReviewType $type
 * @property PeerReviewRole $role
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
        ];
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
