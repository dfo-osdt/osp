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
 *
 * @property int $id
 * @property string $ulid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property ManuscriptRecordType $type primary, secondary, etc.
 * @property ManuscriptRecordStatus $status draft, submitted, etc.
 * @property string $title
 * @property int $region_id
 * @property int $user_id
 * @property string|null $abstract
 * @property string|null $pls Plain Language Summary
 * @property string|null $relevant_to
 * @property string|null $additional_information
 * @property string|null $sent_for_review_at
 * @property string|null $reviewed_at
 * @property string|null $submitted_to_journal_on
 * @property string|null $accepted_on
 * @property string|null $withdrawn_on
 * @property bool $potential_public_interest
 * @property int|null $functional_area_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\FunctionalArea|null $functionalArea
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FundingSource> $fundingSources
 * @property-read int|null $funding_sources_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ManagementReviewStep> $managementReviewSteps
 * @property-read int|null $management_review_steps_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ManuscriptAuthor> $manuscriptAuthors
 * @property-read int|null $manuscript_authors_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ManuscriptPeerReviewer> $peerReviewers
 * @property-read int|null $peer_reviewers_count
 * @property-read \App\Models\Publication|null $publication
 * @property-read \App\Models\Region $region
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Shareable> $shareables
 * @property-read int|null $shareables_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $sharedWithUsers
 * @property-read int|null $shared_with_users_count
 * @property-read \App\Models\User $user
 *
 * @method static \Database\Factories\ManuscriptRecordFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord whereAbstract($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord whereAcceptedOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord whereAdditionalInformation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord whereFunctionalAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord wherePls($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord wherePotentialPublicInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord whereRelevantTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord whereReviewedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord whereSentForReviewAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord whereSubmittedToJournalOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord whereWithdrawnOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord withoutTrashed()
 *
 * @mixin \Eloquent
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
        return $this->hasMany('App\Models\ManuscriptAuthor')->chaperone();
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
