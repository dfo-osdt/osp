<?php

namespace App\Models;

use App\Contracts\Fundable;
use App\Enums\ManuscriptRecordStatus;
use App\Enums\ManuscriptRecordType;
use App\Enums\MediaCollection;
use App\Enums\SensitivityLabel;
use App\Models\Concerns\HasUlid;
use App\Traits\FundableTrait;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Models\ManuscriptRecord
 */
class ManuscriptRecord extends Model implements Fundable, HasMedia
{
    use FundableTrait;
    use HasFactory;
    use HasUlid;
    use InteractsWithMedia;
    use LogsActivity;
    use SoftDeletes;

    public $guarded = [
        'id',
        'ulid',
        'created_at',
        'updated_at',
        'user_id',
        'status',
        'sent_for_review_at',
        'reviewed_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'type' => ManuscriptRecordType::class,
        'status' => ManuscriptRecordStatus::class,
        'potential_public_interest' => 'boolean',
        'apply_ogl' => 'boolean',
    ];

    // default values for optional fields
    protected $attributes = [
        'abstract' => '',
        'pls' => '',
        'relevant_to' => '',
        'public_interest_information' => '',
        'no_ogl_explanation' => '',
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
     * A manuscript has a lead region.
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo('App\Models\Region');
    }

    /**
     * A manuscript has a functional area.
     */
    public function functionalArea(): BelongsTo
    {
        return $this->belongsTo('App\Models\FunctionalArea');
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
     * Sharing relationships.
     */
    public function shareables(): MorphMany
    {
        return $this->morphMany('App\Models\Shareable', 'shareable');
    }

    public function sharedWithUsers(): MorphToMany
    {
        return $this->morphToMany('App\Models\User', 'shareable', 'shareables')
            ->whereHas('sharedWith', function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->withPivot('expires_at', 'can_edit', 'can_delete');
    }

    /**
     * A manuscript has many management review steps.
     */
    public function managementReviewSteps(): HasMany
    {
        return $this->hasMany('App\Models\ManagementReviewStep');
    }

    /**
     * A manuscript has no to many peer reviewers. Only
     * internal publications have peer reviewers.
     */
    public function peerReviewers(): HasMany
    {
        return $this->hasMany(ManuscriptPeerReviewer::class);
    }

    /**
     * A manuscript can have one publication
     */
    public function publication(): HasOne
    {
        return $this->hasOne('App\Models\Publication');
    }

    /**
     * Register media collection that will only accept a single PDF file.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollection::MANUSCRIPT->value)
            ->acceptsMimeTypes(['application/pdf']);
    }

    /**
     * Get manuscript file media model.
     */
    public function getLastManuscriptFile()
    {
        return $this->getMedia(MediaCollection::MANUSCRIPT->value)->last();
    }

    /**
     * Get manuscript files media model.
     */
    public function getManuscriptFiles()
    {
        return $this->getMedia(MediaCollection::MANUSCRIPT->value);
    }

    /**
     * Add a manuscript file to the manuscript record. Since manuscripts are
     * unpublished and should not be shared, we set the sensitivity label
     * to Protected A by default.
     */
    public function addManuscriptFile($file, $preserveOriginal = false)
    {
        return $this->addMedia($file)
            ->withCustomProperties([
                'locked' => false,
                'sensitivity_label' => SensitivityLabel::ProtectedA,
            ])
            ->preservingOriginal($preserveOriginal)
            ->toMediaCollection(MediaCollection::MANUSCRIPT->value);

    }

    public function getManuscriptFile($uuid)
    {
        return $this->getMedia(MediaCollection::MANUSCRIPT->value)->where('uuid', $uuid)->first();
    }

    /**
     * Delete a manuscript file
     */
    public function deleteManuscriptFile($uuid, $force = false)
    {
        $media = $this->getMedia(MediaCollection::MANUSCRIPT->value)->where('uuid', $uuid)->first();
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
     * Lock all manuscript files.
     */
    public function lockManuscriptFiles()
    {
        $this->getManuscriptFiles()->each(function ($media) {
            $media->setCustomProperty('locked', true);
            $media->save();
        });
    }

    /**
     * Validate this manuscript record is filled and can be
     * submitted for internal review.
     *
     * @param bool noException If true, return false instead of throwing a ValidationException on failure.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validateIsFilled(bool $noExceptions = false): bool
    {
        $validator = Validator::make($this->toArray(), [
            'title' => 'required',
            'abstract' => 'required',
            'pls' => 'required',
            'relevant_to' => 'required',
            'region_id' => 'required|exists:regions,id',
            'functional_area_id' => 'required|exists:functional_areas,id',
            'relevant_to' => 'required',
            'no_ogl_explanation' => 'required_if:apply_ogl,false',
        ]);

        $validator->after(function ($validator) {
            if ($this->manuscriptAuthors->where('is_corresponding_author', true)->count() < 1) {
                $validator->errors()->add('manuscript_authors', 'At least one corresponding author is required.');
            }
            if ($this->getMedia('manuscript')->count() < 1) {
                $validator->errors()->add('manuscript_pdf', 'A manuscript file is required.');
            }
        });

        if (! $noExceptions) {
            $validator->validate();
        }

        return $validator->passes();
    }

    /// Scope methods
}
