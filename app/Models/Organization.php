<?php

namespace App\Models;

use App\Observers\OrganizationObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property bool $is_validated
 * @property string $name_en
 * @property string $name_fr
 * @property string|null $abbr_en
 * @property string|null $abbr_fr
 * @property string|null $ror_identifier
 * @property string|null $ror_version
 * @property string|null $ror_names
 * @property string|null $country_code
 * @property-read Collection<int, Author> $authors
 * @property-read int|null $authors_count
 * @property-read Collection<int, ManuscriptAuthor> $manuscriptAuthors
 * @property-read int|null $manuscript_authors_count
 *
 * @method static \Database\Factories\OrganizationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereAbbrEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereAbbrFr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereIsValidated($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereNameFr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereRorIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereRorNames($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereRorVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
#[ObservedBy(OrganizationObserver::class)]
class Organization extends Model
{
    use HasFactory;

    #[\Override]
    public $guarded = [
        'id',
        'created_at',
    ];

    #[\Override]
    public $casts = [
        'is_validated' => 'boolean',
    ];

    // Relationships
    /** Authors
     * @return HasMany<Author, $this> */
    public function authors(): HasMany
    {
        return $this->hasMany(Author::class);
    }

    /** ManuscriptAuthors
     * @return HasMany<ManuscriptAuthor, $this> */
    public function manuscriptAuthors(): HasMany
    {
        return $this->hasMany(ManuscriptAuthor::class);
    }

    /** Get default organization */
    public static function getDefaultOrganization(): Organization
    {
        $org = config('osp.default_organization');

        return self::query()->where('name_en', $org)->firstOrFail();
    }

    /**
     * Get the sentinel organization used as the affiliation
     * of independent researchers (no institutional affiliation).
     * ROR records are ignored so a homonym imported from ROR
     * can never be mistaken for the sentinel.
     */
    public static function getIndependentOrganization(): Organization
    {
        return once(fn (): Organization => self::query()
            ->where('name_en', config('osp.independent_organization'))
            ->whereNull('ror_identifier')
            ->firstOrFail());
    }

    public function isIndependent(): bool
    {
        return $this->id === self::getIndependentOrganization()->id;
    }

    /**
     * Is this name reserved for the independent researcher organization?
     */
    public static function isIndependentName(?string $name): bool
    {
        return strcasecmp(trim((string) $name), (string) config('osp.independent_organization')) === 0;
    }
}
