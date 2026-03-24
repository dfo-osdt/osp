<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $name_en
 * @property string $name_fr
 * @property-read Collection<int, ManuscriptRecord> $manuscriptRecords
 * @property-read int|null $manuscript_records_count
 *
 * @method static \Database\Factories\FunctionalAreaFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FunctionalArea newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FunctionalArea newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FunctionalArea query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FunctionalArea whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FunctionalArea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FunctionalArea whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FunctionalArea whereNameFr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FunctionalArea whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class FunctionalArea extends Model
{
    use HasFactory;

    /**
     * @return HasMany<ManuscriptRecord, $this>
     */
    public function manuscriptRecords(): HasMany
    {
        return $this->hasMany(ManuscriptRecord::class);
    }
}
