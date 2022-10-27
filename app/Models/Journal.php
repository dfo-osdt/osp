<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;

    // static variable for the DFO series publisher
    public static $dfoPublisher = 'Fisheries and Oceans Canada - Pêches et Océans Canada';

    /** Create a scope for DFO series */
    public function scopeDfoSeries($query)
    {
        return $query->where('publisher', Journal::$dfoPublisher);
    }
}
