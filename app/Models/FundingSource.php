<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class FundingSource extends Model
{
    use HasFactory;

    public $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    // Relationships
    public function fundable(): MorphTo
    {
        return $this->morphTo();
    }

    public function funder(): BelongsTo
    {
        return $this->belongsTo(Funder::class);
    }
}
