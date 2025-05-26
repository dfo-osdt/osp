<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $title_en
 * @property string|null $title_fr
 * @property string|null $scopus_source_record_id Scopus source record ID
 * @property string $publisher
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Publication> $publications
 * @property-read int|null $publications_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Journal dfoSeries()
 * @method static \Database\Factories\JournalFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Journal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Journal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Journal query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Journal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Journal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Journal wherePublisher($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Journal whereScopusSourceRecordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Journal whereTitleEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Journal whereTitleFr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Journal whereUpdatedAt($value)
 *
 * @property string $title
 * @property string|null $issn
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Journal notDfoSeries()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Journal whereIssn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Journal whereTitle($value)
 *
 * @mixin \Eloquent
 */
class Journal extends Model
{
    use HasFactory;

    // Static value for the DFO series publisher - if this is changed once it is in the
    // production database it will will also need to be changed in the database!
    public static $dfoPublisher = 'Fisheries and Oceans Canada - Pêches et Océans Canada';

    /** Create a scope for DFO series */
    public function scopeDfoSeries($query)
    {
        return $query->where('publisher', Journal::$dfoPublisher);
    }

    /** Create a scope for DFO series */
    public function scopeNotDfoSeries($query)
    {
        return $query->where('publisher', '!=', Journal::$dfoPublisher);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Publication, $this>
     */
    public function publications(): HasMany
    {
        return $this->hasMany(Publication::class);
    }

    public function isDfoSeries(): bool
    {
        return $this->publisher === Journal::$dfoPublisher;
    }
}
