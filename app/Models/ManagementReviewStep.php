<?php

namespace App\Models;

use App\Enums\ManagementReviewStepDecision;
use App\Enums\ManagementReviewStepStatus;
use App\Observers\ManagementReviewStepObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $manuscript_record_id
 * @property int|null $previous_step_id
 * @property int $user_id
 * @property Carbon|null $completed_at
 * @property string|null $comments
 * @property ManagementReviewStepStatus $status
 * @property ManagementReviewStepDecision|null $decision
 * @property Carbon|null $decision_expected_by
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read ManuscriptRecord $manuscriptRecord
 * @property-read ManagementReviewStep|null $previousStep
 * @property-read User $user
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagementReviewStep dueSoon(int $days = 2)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagementReviewStep overdue()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagementReviewStep pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManagementReviewStep pendingForDays(int $days = 4)
 *
 * @mixin \Eloquent
 */
#[ObservedBy(ManagementReviewStepObserver::class)]
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

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
            'decision_expected_by' => 'datetime',
            'status' => ManagementReviewStepStatus::class,
            'decision' => ManagementReviewStepDecision::class,
        ];
    }

    // Default attributes
    protected $attributes = [
        'comments' => '',
        'status' => ManagementReviewStepStatus::PENDING,
        'decision' => ManagementReviewStepDecision::NONE,
    ];

    // logging options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    /**
     * @return BelongsTo<ManuscriptRecord, $this>
     */
    public function manuscriptRecord(): BelongsTo
    {
        return $this->belongsTo(ManuscriptRecord::class);
    }

    /**
     * @return BelongsTo<ManagementReviewStep, $this>
     */
    public function previousStep(): BelongsTo
    {
        return $this->belongsTo(ManagementReviewStep::class, 'previous_step_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    #[Scope]
    protected function overdue(Builder $query): void
    {
        $query->where('status', ManagementReviewStepStatus::PENDING)
            ->where('decision_expected_by', '<', now());
    }

    #[Scope]
    protected function dueSoon(Builder $query, int $days = 2): void
    {
        $query->where('status', ManagementReviewStepStatus::PENDING)
            ->whereBetween('decision_expected_by', [now(), now()->addBusinessDays($days)]);
    }

    #[Scope]
    protected function pending(Builder $query): void
    {
        $query->where('status', ManagementReviewStepStatus::PENDING);
    }

    #[Scope]
    protected function pendingForDays(Builder $query, int $days = 4): void
    {
        $query->where('status', ManagementReviewStepStatus::PENDING)
            ->where('created_at', '<=', now()->subBusinessDays($days));
    }
}
