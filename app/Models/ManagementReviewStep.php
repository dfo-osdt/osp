<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $manuscript_record_id
 * @property int|null $previous_step_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property string|null $comments
 * @property \App\Enums\ManagementReviewStepStatus $status
 * @property \App\Enums\ManagementReviewStepDecision|null $decision
 * @property \Illuminate\Support\Carbon|null $decision_expected_by
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\ManuscriptRecord $manuscriptRecord
 * @property-read ManagementReviewStep|null $previousStep
 * @property-read \App\Models\User $user
 *
 * @method static \Database\Factories\ManagementReviewStepFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagementReviewStep newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagementReviewStep newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagementReviewStep query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagementReviewStep whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagementReviewStep whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagementReviewStep whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagementReviewStep whereDecision($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagementReviewStep whereDecisionExpectedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagementReviewStep whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagementReviewStep whereManuscriptRecordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagementReviewStep wherePreviousStepId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagementReviewStep whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagementReviewStep whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagementReviewStep whereUserId($value)
 *
 * @mixin \Eloquent
 */
class ManagementReviewStep extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'user_id',
        'manuscript_record_id',
        'previous_step_id',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'decision_expected_by' => 'datetime',
        'status' => \App\Enums\ManagementReviewStepStatus::class,
        'decision' => \App\Enums\ManagementReviewStepDecision::class,
    ];

    // Default attributes
    protected $attributes = [
        'comments' => '',
        'status' => \App\Enums\ManagementReviewStepStatus::PENDING,
        'decision' => \App\Enums\ManagementReviewStepDecision::NONE,
    ];

    // logging options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\ManuscriptRecord, $this>
     */
    public function manuscriptRecord(): BelongsTo
    {
        return $this->belongsTo('App\Models\ManuscriptRecord');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\ManagementReviewStep, $this>
     */
    public function previousStep(): BelongsTo
    {
        return $this->belongsTo('App\Models\ManagementReviewStep', 'previous_step_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }

    public function decisionExpectedBy(): Carbon
    {
        return $this->created_at->addBusinessDays(10);
    }
}
