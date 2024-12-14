<?php

namespace App\Models;

use App\Http\Integrations\Orcid\Enums\PeerReviewRole;
use App\Http\Integrations\Orcid\Enums\PeerReviewType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property PeerReviewType $type
 * @property PeerReviewRole $role
 * @property-read \App\Models\Author|null $author
 * @property-read \App\Models\ManuscriptRecord|null $manuscriptRecord
 *
 * @method static \Database\Factories\ManuscriptPeerReviewerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptPeerReviewer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptPeerReviewer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptPeerReviewer query()
 *
 * @mixin \Eloquent
 */
class ManuscriptPeerReviewer extends Model
{
    /** @use HasFactory<\Database\Factories\ManuscriptPeerReviewerFactory> */
    use HasFactory;

    protected $fillable = [
        'manuscript_record_id',
        'author_id',
        'review_date',
    ];

    protected $attributes = [
        'role' => PeerReviewRole::REVIEWER,
        'type' => PeerReviewType::REVIEW,
    ];

    public function casts(): array
    {
        return [
            'review_date' => 'date',
            'type' => PeerReviewType::class,
            'role' => PeerReviewRole::class,
        ];
    }

    public function manuscriptRecord(): BelongsTo
    {
        return $this->belongsTo(ManuscriptRecord::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
}
