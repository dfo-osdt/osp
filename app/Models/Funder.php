<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name_en
 * @property string $name_fr
 * @property int|null $organization_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FundingSource> $fundingSources
 * @property-read int|null $funding_sources_count
 * @property-read \App\Models\Organization|null $organization
 *
 * @method static \Database\Factories\FunderFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Funder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Funder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Funder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Funder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Funder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Funder whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Funder whereNameFr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Funder whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Funder whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Funder extends Model
{
    use HasFactory;

    // Relationships
    public function fundingSources()
    {
        return $this->hasMany(FundingSource::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
