<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property \App\Models\Author|null $author
 * @property \App\Models\Organization|null $organization
 * @property \Carbon\Carbon $start_date
 * @property \Carbon\Carbon|null $end_date
 * @property \Carbon\Carbon|null $orcid_updated_at
 * @property int|null $orcid_putcode
 *
 * @method static \Database\Factories\AuthorEmploymentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorEmployment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorEmployment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorEmployment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorEmployment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorEmployment withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorEmployment withoutTrashed()
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $author_id
 * @property int $organization_id
 * @property string|null $role_title
 * @property string|null $department_name
 * @property-read int|null $activities_count
 * @property int|null $orcid_putcode
 * @property \Illuminate\Support\Carbon|null $orcid_updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorEmployment whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorEmployment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorEmployment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorEmployment whereDepartmentName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorEmployment whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorEmployment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorEmployment whereOrcidPutcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorEmployment whereOrcidUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorEmployment whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorEmployment whereRoleTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorEmployment whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorEmployment whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class AuthorEmployment extends Model
{
    /** @use HasFactory<\Database\Factories\AuthorEmploymentFactory> */
    use HasFactory;

    use LogsActivity;
    use SoftDeletes;

    public $guarded = [
        'id',
        'created_at',
        'updated_at',
        'orcid_putcode',
        'orcid_updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array{
     *  start_date: 'date',
     *  end_date: 'date',
     *  orcid_updated_at: 'datetime'
     * }
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'orcid_updated_at' => 'datetime',
        ];
    }

    // logging options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function isSyncedWithOrcid(): bool
    {
        return $this->orcid_putcode !== null;
    }

    public function needsSyncWithOrcid(): bool
    {
        return $this->orcid_updated_at === null || $this->updated_at->gt($this->orcid_updated_at);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * Note that we allow a different organization here instead
     * of the one in the author's profile as the author may
     * have had been employed by a different organization
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Organization, $this>
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
