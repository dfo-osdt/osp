<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    use HasFactory;

    public $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    // Relationships

    /** ManuscriptAuthors */
    public function manuscriptAuthors(): HasMany
    {
        return $this->hasMany('App\Models\ManuscriptAuthor');
    }

    /** Organization */
    public function organization(): BelongsTo
    {
        return $this->belongsTo('App\Models\Organization');
    }
}
