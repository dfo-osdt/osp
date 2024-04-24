<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FunctionalArea extends Model
{
    use HasFactory;

    public function manuscriptRecords(): HasMany
    {
        return $this->hasMany('App\Models\ManuscriptRecord');
    }
}
