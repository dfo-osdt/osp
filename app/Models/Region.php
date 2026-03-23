<?php

namespace App\Models;

use App\Enums\Permissions\UserRole;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $name_en
 * @property string $name_fr
 * @property string $slug
 * @property bool $enforce_secondary_review_deadline
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region whereNameFr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region whereSlug($value)
 *
 * @mixin \Eloquent
 */
class Region extends Model
{
    protected $fillable = [
        'enforce_secondary_review_deadline',
    ];

    protected function casts(): array
    {
        return [
            'enforce_secondary_review_deadline' => 'boolean',
        ];
    }

    /**
     * Return this region's editors
     *
     * @return Collection<int, User>
     */
    public function getRegionalEditors(): Collection
    {
        $role = UserRole::from($this->slug.'_editor');

        return User::query()->role($role->value)->get();

    }
}
