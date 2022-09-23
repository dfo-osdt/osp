<?php

namespace App\Models;

use App\Enums\ManuscriptRecordStatus;
use App\Enums\ManuscriptRecordType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\ManuscriptRecord
 */
class ManuscriptRecord extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    public $guarded = [
        'id',
        'created_at',
        'updated_at',
        'user_id',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'type' => ManuscriptRecordType::class,
        'status' => ManuscriptRecordStatus::class,
    ];

    // default values for optional fields
    protected $attributes = [
        'abstract' => '',
        'pls' => '',
        'scientific_implications' => '',
        'regions_and_species' => '',
        'relevant_to' => '',
        'additional_information' => '',
    ];

    // Relationships

    /**
     * A manuscript has a lead region.
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo('App\Models\Region');
    }

    /**
     * A manuscripts has several ManuscriptAuthors
     */
    public function manuscriptAuthors(): HasMany
    {
        return $this->hasMany('App\Models\ManuscriptAuthor');
    }

    /**
     * A manuscript has a user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Register media collection that will only accept a single PDF file.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('manuscript')
            ->acceptsMimeTypes(['application/pdf']);
    }

    /**
     * Get manuscript file media model.
     */
    public function getManuscriptFile(): ?Media
    {
        return $this->getMedia('manuscript')->last();
    }
}
