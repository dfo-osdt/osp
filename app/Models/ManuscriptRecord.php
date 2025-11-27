<?php

namespace App\Models;

use App\Contracts\Fundable;
use App\Contracts\Plannable;
use App\Enums\ManuscriptRecordStatus;
use App\Enums\ManuscriptRecordType;
use App\Enums\MediaCollection;
use App\Enums\SensitivityLabel;
use App\Traits\FundableTrait;
use App\Traits\PlannableTrait;
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
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Models\ManuscriptRecord
 *
 * @property int $id
 * @property \Ramsey\Uuid\UuidInterface|string $ulid
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
 * @property string|null $withdraw_reason
 * @property bool $potential_public_interest
 * @property string|null $pls_source_language
 * @property bool $pls_approved_by_author
 * @property bool $pls_translation_approved
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
 * @property string|null $pls_en Plain Language Summary
 * @property string|null $public_interest_information
 * @property bool $apply_ogl
 * @property string|null $no_ogl_explanation
 * @property string|null $preprint_url
 * @property bool $intends_open_access
 * @property string|null $open_access_rationale
 * @property string|null $pls_fr
 * @property-read \App\Models\PlanningBinderItem|null $planningBinderItem
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord whereApplyOgl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord whereIntendsOpenAccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord whereNoOglExplanation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord whereOpenAccessRationale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord wherePlsEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord wherePlsFr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord wherePreprintUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManuscriptRecord wherePublicInterestInformation($value)
 *
 * @mixin \Eloquent
 */
class ManuscriptRecord extends Model implements Fundable, HasMedia, Plannable
{
    use FundableTrait;
    use HasFactory;
    use InteractsWithMedia;
    use LogsActivity;
    use PlannableTrait;
    use SoftDeletes;

    protected $ulid;

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
        'intends_open_access' => 'boolean',
        'pls_approved_by_author' => 'boolean',
        'pls_translation_approved' => 'boolean',
    ];

    // default values for optional fields
    protected $attributes = [
        'abstract' => '',
        'pls_en' => '',
        'pls_fr' => '',
        'relevant_to' => '',
        'public_interest_information' => '',
        'no_ogl_explanation' => '',
        'open_access_rationale' => '',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (ManuscriptRecord $manuscript): void {
            $manuscript->generateUlid();
        });
    }

    protected function generateUlid(): void
    {
        $this->attributes['ulid'] = Str::ulid();
    }

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
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Region, $this>
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Region::class);
    }

    /**
     * A manuscript has a functional area.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\FunctionalArea, $this>
     */
    public function functionalArea(): BelongsTo
    {
        return $this->belongsTo(\App\Models\FunctionalArea::class);
    }

    /**
     * A manuscripts has several ManuscriptAuthors
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\ManuscriptAuthor, $this>
     */
    public function manuscriptAuthors(): HasMany
    {
        return $this->hasMany(\App\Models\ManuscriptAuthor::class)->chaperone();
    }

    /**
     * A manuscript has a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Sharing relationships.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany<\App\Models\Shareable, $this>
     */
    public function shareables(): MorphMany
    {
        return $this->morphMany(\App\Models\Shareable::class, 'shareable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany<\App\Models\User, $this>
     */
    public function sharedWithUsers(): MorphToMany
    {
        return $this->morphToMany(\App\Models\User::class, 'shareable', 'shareables')
            ->whereHas('sharedWith', function ($query): void {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->withPivot('expires_at', 'can_edit', 'can_delete');
    }

    /**
     * A manuscript has many management review steps.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\ManagementReviewStep, $this>
     */
    public function managementReviewSteps(): HasMany
    {
        return $this->hasMany(\App\Models\ManagementReviewStep::class);
    }

    /**
     * A manuscript has no to many peer reviewers. Only
     * internal publications have peer reviewers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\ManuscriptPeerReviewer, $this>
     */
    public function peerReviewers(): HasMany
    {
        return $this->hasMany(ManuscriptPeerReviewer::class);
    }

    /**
     * A manuscript can have one publication
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<\App\Models\Publication, $this>
     */
    public function publication(): HasOne
    {
        return $this->hasOne(\App\Models\Publication::class);
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
    public function getManuscriptFiles(): \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection
    {
        return $this->getMedia(MediaCollection::MANUSCRIPT->value);
    }

    /**
     * Add a manuscript file to the manuscript record. Since manuscripts are
     * unpublished and should not be shared, we set the sensitivity label
     * to Protected A by default.
     */
    public function addManuscriptFile(string|\Symfony\Component\HttpFoundation\File\UploadedFile $file, bool $preserveOriginal = false): \Spatie\MediaLibrary\MediaCollections\Models\Media
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
    public function deleteManuscriptFile($uuid, $force = false): void
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
    public function lockManuscriptFiles(): void
    {
        $this->getManuscriptFiles()->each(function ($media): void {
            $media->setCustomProperty('locked', true);
            $media->save();
        });
    }

    /**
     * Validate this manuscript record is filled and can be
     * submitted for internal review.
     *
     * @param  bool  $noExceptions  If true, return false instead of throwing a ValidationException on failure.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validateIsFilled(bool $noExceptions = false): bool
    {
        $validator = Validator::make($this->toArray(), [
            'title' => ['required'],
            'abstract' => ['required'],
            'pls_en' => ['required_if:pls_source_language,en'],
            'pls_fr' => ['required_if:pls_source_language,fr'],
            'pls_source_language' => ['required', 'in:en,fr'],
            'pls_approved_by_author' => ['required', 'accepted'],
            'relevant_to' => ['required'],
            'region_id' => ['required', 'exists:regions,id'],
            'functional_area_id' => ['required', 'exists:functional_areas,id'],
            'no_ogl_explanation' => ['required_if:apply_ogl,false'],
            'open_access_rationale' => ['required_if:intends_open_access,false'],
        ]);

        $validator->after(function ($validator): void {
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

    // / Scope methods
}
