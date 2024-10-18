<?php

namespace App\Models;

use App\Traits\HasExpertises;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Author extends Model
{
    use HasExpertises;
    use HasFactory;
    use LogsActivity;

    public $guarded = [
        'id',
        'created_at',
        'updated_at',
        'orcid_verified',
        'orcid_access_token',
        'orcid_token_scope',
        'orcid_refresh_token',
    ];

    protected $casts = [
        'orcid_verified' => 'boolean',
        'orcid_access_token' => 'encrypted',
        'orcid_refresh_token' => 'encrypted',
    ];

    // logging options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->logExcept(['orcid_access_token', 'orcid_refresh_token']);
    }

    // author full name
    public function getFullNameAttribute(): string
    {
        return $this->first_name.' '.$this->last_name;
    }

    // author name for APA citation
    public function getApaNameAttribute(): string
    {
        return $this->last_name.', '.$this->first_name;
    }

    /**
     * Get the ORCID number from the ORCID iD string as
     * we store thee full ORCID iD with the URL prefix
     * in the database.
     */
    public function getOrcidNumberAttribute(): string
    {
        if (! $this->orcid) {
            return '';
        }

        return substr($this->orcid, -19);
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

    /**
     * Clear the ORCID token and related fields.
     * This is used when the user revokes the ORCID token.
     */
    public function clearOrcidToken(): void
    {
        $this->orcid_access_token = null;
        $this->orcid_refresh_token = null;
        $this->orcid_token_scope = null;
        $this->orcid_expires_at = null;
        $this->orcid_verified = false;
        $this->save();
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

    public function employments(): HasMany
    {
        return $this->hasMany('App\Models\AuthorEmployment');
    }
}
