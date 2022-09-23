<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\ManuscriptAuthor
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $manuscript_record_id
 * @property int $author_id
 * @property int $organization_id
 * @property bool $is_corresponding_author
 * @property-read \App\Models\Author $author
 * @property-read \App\Models\ManuscriptRecord $manuscriptRecord
 * @property-read \App\Models\Organization $organization
 *
 * @method static \Database\Factories\ManuscriptAuthorFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptAuthor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptAuthor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptAuthor query()
 * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptAuthor whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptAuthor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptAuthor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptAuthor whereIsCorrespondingAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptAuthor whereManuscriptRecordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptAuthor whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ManuscriptAuthor whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ManuscriptAuthor extends Model
{
    use HasFactory;

    public $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public $casts = [
        'is_corresponding_author' => 'boolean',
    ];

    // update the manuscript's updated_at timestamp when the author is updated
    protected $touches = ['manuscriptRecord'];

    // Relationships

    /**
     * A manuscript author belongs to a manuscript.
     */
    public function manuscriptRecord(): BelongsTo
    {
        return $this->belongsTo('App\Models\ManuscriptRecord');
    }

    /** Organization */
    public function organization(): BelongsTo
    {
        return $this->belongsTo('App\Models\Organization');
    }

    /** Author */
    public function author(): BelongsTo
    {
        return $this->belongsTo('App\Models\Author');
    }
}
