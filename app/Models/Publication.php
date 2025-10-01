<?php

namespace App\Models;

use App\Contracts\Fundable;
use App\Contracts\Plannable;
use App\Enums\MediaCollection;
use App\Enums\Permissions\UserPermission;
use App\Enums\PublicationStatus;
use App\Enums\SensitivityLabel;
use App\Enums\SupplementaryFileType;
use App\Traits\FundableTrait;
use App\Traits\PlannableTrait;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property PublicationStatus $status accepted, published, etc.
 * @property string $title
 * @property string|null $doi
 * @property \Illuminate\Support\Carbon|null $accepted_on
 * @property \Illuminate\Support\Carbon|null $published_on
 * @property \Illuminate\Support\Carbon|null $embargoed_until
 * @property bool $is_open_access
 * @property int|null $manuscript_record_id
 * @property int $journal_id
 * @property int $user_id
 * @property int $region_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FundingSource> $fundingSources
 * @property-read int|null $funding_sources_count
 * @property-read \App\Models\Journal $journal
 * @property-read \App\Models\ManuscriptRecord|null $manuscriptRecord
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PublicationAuthor> $publicationAuthors
 * @property-read int|null $publication_authors_count
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Region $region
 *
 * @method static \Database\Factories\PublicationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication notUnderEmbargo()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication openAccess()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication secondaryPublication()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication underEmbargo()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereAcceptedOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereDoi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereEmbargoedUntil($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereIsOpenAccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereJournalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereManuscriptRecordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication wherePublishedOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication withoutTrashed()
 *
 * @property-read \App\Models\PlanningBinderItem|null $planningBinderItem
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereRegionId($value)
 *
 * @mixin \Eloquent
 */
class Publication extends Model implements Fundable, HasMedia, Plannable
{
    use FundableTrait;
    use HasFactory;
    use InteractsWithMedia;
    use LogsActivity;
    use PlannableTrait;
    use SoftDeletes;

    public $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public $casts = [
        'is_open_access' => 'boolean',
        'accepted_on' => 'date',
        'published_on' => 'date',
        'embargoed_until' => 'date',
        'status' => PublicationStatus::class,
    ];

    public $attributes = [
        // default is false
        'is_open_access' => false,
        // default is accepted
        'status' => PublicationStatus::ACCEPTED,
    ];

    // logging options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    // Relationships
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Journal, $this>
     */
    public function journal(): BelongsTo
    {
        return $this->belongsTo(Journal::class);
    }

    /**
     * Manuscript record - can be null as we allow creation of
     * publications without a manuscript record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\ManuscriptRecord, $this>
     */
    public function manuscriptRecord(): BelongsTo
    {
        return $this->belongsTo(ManuscriptRecord::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The Lead region for which the publication is associated with.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Region, $this>
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\PublicationAuthor, $this>
     */
    public function publicationAuthors(): HasMany
    {
        return $this->hasMany(PublicationAuthor::class);
    }

    // media
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollection::PUBLICATION->value)
            ->acceptsMimeTypes(['application/pdf']);

        $this->addMediaCollection(MediaCollection::SUPPLEMENTARY_FILE->value)
            ->acceptsMimeTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']);
    }

    /**
     * Add publication file media model.
     */
    public function addPublicationFile($file, $preserveOriginal = false): Media
    {
        return $this->addMedia($file)
            ->preservingOriginal($preserveOriginal)
            ->toMediaCollection(MediaCollection::PUBLICATION->value);
    }

    /**
     * Get publication file media model.
     */
    public function getPublicationFile($uuid)
    {
        return $this->getMedia(MediaCollection::PUBLICATION->value)->where('uuid', $uuid)->first();
    }

    /**
     * Delete publication file media model.
     */
    public function deletePublicationFile($uuid, $force = false): void
    {
        $media = $this->getPublicationFile($uuid);
        if (! $media) {
            throw new FileNotFoundException('File not found.');
        }
        if ($force) {
            $media->delete();

            return;
        }
        if ($media->getCustomProperty('locked', false)) {
            throw new Exception('Cannot delete locked file.');
        }
        $media->delete();
    }

    /**
     * Add supplementary file media model.
     */
    public function addSupplementaryFile(string|UploadedFile $file, SupplementaryFileType $type, ?string $description = null, $preserveOriginal = false): Media
    {

        $properties = [
            'supplementary_file_type' => $type->value,
        ];
        if ($description) {
            $properties['description'] = $description;
        }

        if (SupplementaryFileType::isProtectedA($type)) {
            $properties['sensitivity_label'] = SensitivityLabel::ProtectedA->value;
        }

        return $this->addMedia($file)
            ->preservingOriginal($preserveOriginal)
            ->withCustomProperties($properties)
            ->toMediaCollection(MediaCollection::SUPPLEMENTARY_FILE->value);
    }

    /**
     * Get supplementary file media model.
     */
    public function getSupplementaryFile($uuid)
    {
        return $this->getMedia(MediaCollection::SUPPLEMENTARY_FILE->value)->where('uuid', $uuid)->first();
    }

    /**
     * Delete supplementary file media model.
     */
    public function deleteSupplementaryFile($uuid, $force = false): void
    {
        $media = $this->getSupplementaryFile($uuid);
        if (! $media) {
            throw new FileNotFoundException('File not found.');
        }
        if ($force) {
            $media->delete();

            return;
        }
        if ($media->getCustomProperty('locked', false)) {
            throw new Exception('Cannot delete locked file.');
        }
        $media->delete();
    }

    /** Is the publication under embargo? */
    public function isUnderEmbargo(): bool
    {
        if ($this->is_open_access) {
            return false;
        }

        return $this->embargoed_until && $this->embargoed_until->isFuture();
    }

    // Scopes

    /** Get the open access publications */
    public function scopeOpenAccess($query)
    {
        return $query->where('is_open_access', true);
    }

    /** Get the publication that are no longer under embargo */
    public function scopeNotUnderEmbargo($query)
    {
        return $query->where('embargoed_until', '<', now())->orWhere('is_open_access', true);
    }

    /** Get the publications under embargo */
    public function scopeUnderEmbargo($query)
    {
        return $query->where('embargoed_until', '>=', now());
    }

    /** Secondary publications */
    public function scopeSecondaryPublication($query)
    {
        return $query->whereIn('journal_id', Journal::dfoSeries()->pluck('id'));
    }

    /**
     * Scope publications based on user permissions
     * - Admins see all publications
     * - Regional editors see all published + unpublished in their region
     * - Regular users see only published publications
     */
    public function scopeForUser($query, User $user)
    {
        // if user has permission to update all publications, show everything
        if ($user->hasPermissionTo(UserPermission::UPDATE_PUBLICATIONS)) {
            return $query;
        }

        // if user is a regional editor, show published + unpublished in their region
        if ($user->isRegionalEditor()) {
            $permissions = collect(UserPermission::getRegionalEditorPubEditPermissions())->pluck('value');
            $userPermissions = $user->getAllPermissions()->pluck('name');
            $slugs = $permissions->intersect($userPermissions)
                ->map(fn ($perm) => explode('_', $perm)[2] ?? null)
                ->except(null)->toArray();

            return $query->where('status', PublicationStatus::PUBLISHED)
                ->orWhere(function ($q) use ($slugs) {
                    $q->where('status', PublicationStatus::ACCEPTED)
                        ->whereHas('region', function ($regionQuery) use ($slugs) {
                            $regionQuery->whereIn('slug', $slugs);
                        });
                });
        }

        // regular users only see published publications
        return $query->where('status', PublicationStatus::PUBLISHED);
    }
}
