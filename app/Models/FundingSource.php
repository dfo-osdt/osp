<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $title
 * @property string|null $description
 * @property int $funder_id
 * @property string $fundable_type
 * @property int $fundable_id
 * @property-read Model|\Eloquent $fundable
 * @property-read Funder $funder
 *
 * @method static \Database\Factories\FundingSourceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundingSource newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundingSource newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundingSource query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundingSource whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundingSource whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundingSource whereFundableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundingSource whereFundableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundingSource whereFunderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundingSource whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundingSource whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundingSource whereUpdatedAt($value)
 *
 * @property Carbon|null $deleted_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundingSource onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundingSource whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundingSource withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundingSource withoutTrashed()
 *
 * @mixin \Eloquent
 */
class FundingSource extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    // Relationships
    public function fundable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo<Funder, $this>
     */
    public function funder(): BelongsTo
    {
        return $this->belongsTo(Funder::class);
    }
}
