<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $name_en
 * @property string $name_fr
 * @property bool $is_validated
 *
 * @method static \Database\Factories\ExpertiseFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expertise newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expertise newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expertise query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expertise whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expertise whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expertise whereIsValidated($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expertise whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expertise whereNameFr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expertise whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Expertise extends Model
{
    use HasFactory;
    use HasUlids;

    public $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_validated' => 'boolean',
        ];
    }

    protected function authors()
    {
        return $this->morphedByMany(Author::class, 'expertiseable');
    }

    #[Scope]
    protected function scopeValidated($query)
    {
        $query->where('is_validated', true);
    }

    #[Scope]
    protected function used($query)
    {
        $query->whereHas('authors');
    }
}
