<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
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
 * @property-read Collection<int, Author> $authors
 *
 * @method static \Database\Factories\SectorFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sector newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sector newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sector query()
 *
 * @mixin \Eloquent
 */
#[Fillable(['name_en', 'name_fr'])]
class Sector extends Model
{
    use HasFactory;

    /**
     * @return HasMany<Author, $this>
     */
    public function authors(): HasMany
    {
        return $this->hasMany(Author::class);
    }

    /**
     * Is this sector referenced by an author or an authorship snapshot?
     * Used to prevent deleting a sector that holds historical data.
     */
    public function isInUse(): bool
    {
        return $this->authors()->exists()
            || ManuscriptAuthor::query()->where('sector_id', $this->id)->exists()
            || PublicationAuthor::withTrashed()->where('sector_id', $this->id)->exists();
    }
}
