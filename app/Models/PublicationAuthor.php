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
 * @property int $publication_id
 * @property int $author_id
 * @property int $organization_id
 * @property bool $is_corresponding_author
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Author $author
 * @property-read \App\Models\Organization $organization
 * @property-read \App\Models\Publication $publication
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
 * @mixin \Eloquent
 */
class PublicationAuthor extends Model
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

    // update the publication's updated_at timestamp when the author is updated
    protected $touches = ['publication'];

    // logging options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    // Relationships
    /**
     * A publication author belongs to a publication.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Publication, $this>
     */
    public function publication(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Publication::class);
    }

    /** Organization
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Organization, $this> */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Organization::class);
    }

    /** Author
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Author, $this> */
    public function author(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Author::class);
    }
}
