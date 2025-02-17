<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Models\Activity;

class ActivityLog extends Activity
{
    use HasFactory;

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
    ];
}
