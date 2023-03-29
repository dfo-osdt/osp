<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
