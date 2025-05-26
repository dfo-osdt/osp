<?php

namespace App\Models;

use App\Enums\ManuscriptRecordType;
use App\Enums\PlanningBinder\PlanningBinderItemStatus;
use Glhd\Bits\Database\HasSnowflakes;
use Glhd\Bits\Snowflake;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Snowflake $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $plannable_type
 * @property int $plannable_id
 * @property ManuscriptRecordType $type
 * @property PlanningBinderItemStatus $status
 * @property string|null $notes
 * @property int|null $region_id
 * @property string|null $anticipated_date_of_publication
 * @property string|null $communication_approach
 * @property-read Model|\Eloquent $plannable
 * @property-read \App\Models\Region|null $region
 *
 * @method static \Database\Factories\PlanningBinderItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanningBinderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanningBinderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanningBinderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanningBinderItem whereAnticipatedDateOfPublication($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanningBinderItem whereCommunicationApproach($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanningBinderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanningBinderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanningBinderItem whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanningBinderItem wherePlannableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanningBinderItem wherePlannableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanningBinderItem whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanningBinderItem whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanningBinderItem whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanningBinderItem whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class PlanningBinderItem extends Model
{
    /** @use HasFactory<\Database\Factories\PlanningBinderItemFactory> */
    use HasFactory;

    use HasSnowflakes;

    protected function casts(): array
    {
        return [
            'id' => Snowflake::class,
            'anticipated_publication_date' => 'datetime',
            'type' => ManuscriptRecordType::class,
            'status' => PlanningBinderItemStatus::class,
        ];
    }

    protected $guarded = [];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function plannable()
    {
        return $this->morphTo();
    }
}
