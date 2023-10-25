<?php

namespace App\Models;

use App\Traits\HasExpertises;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    use HasFactory;
    use HasExpertises;

    public $guarded = [
        'id',
        'created_at',
        'updated_at',
        'orcid_verified',
        'orcid_access_token',
    ];

    protected $casts = [
        'orcid_verified' => 'boolean',
    ];

    // author full name
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    // author name for APA citation
    public function getApaNameAttribute(): string
    {
        return $this->last_name . ', ' . $this->first_name;
    }

    /**
     * Make sure that the email is always stored as lowercase to prevent duplicates.
     */
    protected function email(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value,
            set: fn ($value) => strtolower($value),
        );
    }

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

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }
}
