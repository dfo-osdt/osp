<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManuscriptAuthor extends Model
{
    use HasFactory;

    public $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public $casts = [
        'is_corresponding_author' => 'boolean',
    ];

    // Relationships

    /**
     * A manuscript author belongs to a manuscript.
     */
    public function manuscriptRecord(): BelongsTo
    {
        return $this->belongsTo('App\Models\ManuscriptRecord');
    }

    /** Organization */
    public function organization(): BelongsTo
    {
        return $this->belongsTo('App\Models\Organization');
    }

    /** Author */
    public function author(): BelongsTo
    {
        return $this->belongsTo('App\Models\Author');
    }
}
