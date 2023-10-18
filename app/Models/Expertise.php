<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Expertise extends Model
{
    use HasFactory;

    public $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public $with = [
        'parent'
    ];

    // Relationships
    public function authors(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\Author');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo('App\Models\Expertise', 'parent_tid', 'tid');
    }

    public function children(): HasMany
    {
        return $this->hasMany('App\Models\Expertise', 'parent_tid', 'tid');
    }

    // Main expertise scope - where there is no parent (tid == null)
    public function scopeMainExpertise($query)
    {
        return $query->whereNull('parent_tid');
    }
}
