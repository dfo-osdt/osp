<?php

namespace App\Models;

use App\Enums\PublicationStatus;
use App\Traits\HasExpertises;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $first_name
 * @property string $last_name
 * @property string|null $orcid
 * @property bool|null $orcid_verified
 * @property mixed|null $orcid_access_token
 * @property string|null $orcid_token_scope
 * @property mixed|null $orcid_refresh_token
 * @property \Illuminate\Support\Carbon|null $orcid_expires_at
 * @property string $email
 * @property int $organization_id
 * @property int|null $user_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AuthorEmployment> $employments
 * @property-read int|null $employments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Expertise> $expertises
 * @property-read int|null $expertises_count
 * @property-read string $apa_name
 * @property-read string $full_name
 * @property-read string $orcid_number
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ManuscriptAuthor> $manuscriptAuthors
 * @property-read int|null $manuscript_authors_count
 * @property-read \App\Models\Organization $organization
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ManuscriptPeerReviewer> $peerReviews
 * @property-read int|null $peer_reviews_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Publication> $publications
 * @property-read int|null $publications_count
 * @property-read \App\Models\User|null $user
 *
 * @method static Builder<static>|Author externalAuthor()
 * @method static \Database\Factories\AuthorFactory factory($count = null, $state = [])
 * @method static Builder<static>|Author internalAuthor()
 * @method static Builder<static>|Author newModelQuery()
 * @method static Builder<static>|Author newQuery()
 * @method static Builder<static>|Author query()
 * @method static Builder<static>|Author whereCreatedAt($value)
 * @method static Builder<static>|Author whereEmail($value)
 * @method static Builder<static>|Author whereFirstName($value)
 * @method static Builder<static>|Author whereId($value)
 * @method static Builder<static>|Author whereLastName($value)
 * @method static Builder<static>|Author whereOrcid($value)
 * @method static Builder<static>|Author whereOrcidAccessToken($value)
 * @method static Builder<static>|Author whereOrcidExpiresAt($value)
 * @method static Builder<static>|Author whereOrcidRefreshToken($value)
 * @method static Builder<static>|Author whereOrcidTokenScope($value)
 * @method static Builder<static>|Author whereOrcidVerified($value)
 * @method static Builder<static>|Author whereOrganizationId($value)
 * @method static Builder<static>|Author whereUpdatedAt($value)
 * @method static Builder<static>|Author whereUserId($value)
 * @method static Builder<static>|Author withOrcid()
 *
 * @mixin \Eloquent
 */
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
        'orcid_expires_at' => 'datetime',
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

    /**
     * Does this author have a valid ORCID connection
     * according to the token and the verified flag?
     */
    public function hasValidOrcidCredentials(): bool
    {
        if (! $this->orcid_verified) {
            return false;
        }
        if (! $this->orcid_access_token) {
            return false;
        }
        if ($this->orcid_expires_at < now()) {
            return false;
        }

        return true;
    }

    // Relationships
    /** ManuscriptAuthors
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\ManuscriptAuthor, $this> */
    public function manuscriptAuthors(): HasMany
    {
        return $this->hasMany('App\Models\ManuscriptAuthor');
    }

    public function manuscriptRecords(): BelongsToMany
    {
        return $this->belongsToMany(ManuscriptRecord::class, 'manuscript_authors');
    }

    /** Pubslihed publications
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<\App\Models\Publication, $this> */
    public function publications(): BelongsToMany
    {
        return $this->belongsToMany(Publication::class, 'publication_authors')->where('status', PublicationStatus::PUBLISHED);
    }

    /** Organization
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Organization, $this> */
    public function organization(): BelongsTo
    {
        return $this->belongsTo('App\Models\Organization');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\AuthorEmployment, $this>
     */
    public function employments(): HasMany
    {
        return $this->hasMany('App\Models\AuthorEmployment');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\ManuscriptPeerReviewer, $this>
     */
    public function peerReviews(): HasMany
    {
        return $this->hasMany('App\Models\ManuscriptPeerReviewer');
    }

    public function scopeInternalAuthor(Builder $query): void
    {
        $Organization = Organization::getDefaultOrganization();
        $query->where('organization_id', $Organization->id);
    }

    public function scopeExternalAuthor(Builder $query): void
    {
        $Organization = Organization::getDefaultOrganization();
        $query->where('organization_id', '!=', $Organization->id);
    }

    public function scopeWithOrcid(Builder $query): void
    {
        $query->whereNotNull('orcid');
    }
}
