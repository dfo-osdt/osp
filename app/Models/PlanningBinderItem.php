<?php

namespace App\Models;

use App\Enums\ManuscriptRecordType;
use App\Enums\PlanningBinder\PlanningBinderItemStatus;
use Glhd\Bits\Database\HasSnowflakes;
use Glhd\Bits\Snowflake;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
