<?php

namespace App\Models;

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
 * @property bool $is_corresponding_author
 * @property bool $is_group_author
 * @property-read Collection<int, Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @property-read Author $author
 * @property-read Organization $organization
 * @property-read Publication $publication
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
 *
 * @mixin \Eloquent
 */
#[Touches(['publication'])]
class PublicationAuthor extends Model
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    public $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public $casts = [
        'is_corresponding_author' => 'boolean',
        'is_group_author' => 'boolean',
    ];

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

    /** Author
     * @return BelongsTo<Author, $this> */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
}
