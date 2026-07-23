<?php

namespace App\Models;

use Database\Factories\RegionNotificationGroupMemberFactory;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * @property int $id
 * @property int $region_id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @property-read Region $region
 * @property-read User $user
 *
 * @method static \Database\Factories\RegionNotificationGroupMemberFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RegionNotificationGroupMember newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RegionNotificationGroupMember newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RegionNotificationGroupMember query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RegionNotificationGroupMember whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RegionNotificationGroupMember whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RegionNotificationGroupMember whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RegionNotificationGroupMember whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RegionNotificationGroupMember whereUserId($value)
 *
 * @mixin \Eloquent
 */
#[Guarded([
    'id',
    'created_at',
    'updated_at',
])]
class RegionNotificationGroupMember extends Model
{
    /** @use HasFactory<RegionNotificationGroupMemberFactory> */
    use HasFactory;

    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    /**
     * @return BelongsTo<Region, $this>
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
