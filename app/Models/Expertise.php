<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Expertise extends Model
{
    use HasUlids;
    use HasFactory;

    public $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];
}
