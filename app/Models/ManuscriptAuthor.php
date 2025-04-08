<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $manuscript_record_id
 * @property int $author_id
 * @property int $organization_id
 * @property bool $is_corresponding_author
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Author $author
 * @property-read \App\Models\ManuscriptRecord $manuscriptRecord
 * @property-read \App\Models\Organization $organization
 *
 * @method static \Database\Factories\ManuscriptAuthorFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptAuthor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptAuthor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptAuthor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptAuthor whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptAuthor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptAuthor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptAuthor whereIsCorrespondingAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptAuthor whereManuscriptRecordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptAuthor whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptAuthor whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class ManuscriptAuthor extends Model
{
    use HasFactory;
    use LogsActivity;

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

    // logging options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    // Relationships
    /**
     * A manuscript author belongs to a manuscript.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\ManuscriptRecord, $this>
     */
    public function manuscriptRecord(): BelongsTo
    {
        return $this->belongsTo('App\Models\ManuscriptRecord');
    }

    /** Organization
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Organization, $this> */
    public function organization(): BelongsTo
    {
        return $this->belongsTo('App\Models\Organization');
    }

    /** Author
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Author, $this> */
    public function author(): BelongsTo
    {
        return $this->belongsTo('App\Models\Author');
    }
}
