<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function publications(): HasMany
    {
        return $this->hasMany(Publication::class);
    }
}
