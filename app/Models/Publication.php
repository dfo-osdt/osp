<?php

namespace App\Models;

use App\Contracts\Fundable;
use App\Enums\MediaCollection;
use App\Enums\PublicationStatus;
use App\Enums\SensitivityLabel;
use App\Enums\SupplementaryFileType;
use App\Traits\FundableTrait;
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

class Publication extends Model implements Fundable, HasMedia
{
    use FundableTrait;
    use HasFactory;
    use InteractsWithMedia;
    use LogsActivity;
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
    public function journal(): BelongsTo
    {
        return $this->belongsTo(Journal::class);
    }

    /**
     * Manuscript record - can be null as we allow creation of
     * publications without a manuscript record.
     */
    public function manuscriptRecord(): BelongsTo
    {
        return $this->belongsTo(ManuscriptRecord::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

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
            ->acceptsMimeTypes(['application/pdf']);
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
}
