<?php

namespace App\Models;

use Database\Factories\ManagementReviewDelegationFactory;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * @property int $id
 * @property int $user_id
 * @property int $delegate_user_id
 * @property Carbon|null $starts_at
 * @property Carbon $ends_at
 * @property Carbon|null $ended_early_at
 * @property string|null $comment
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @property-read User $delegate
 * @property-read User $user
 *
 * @method static Builder<static>|ManagementReviewDelegation active()
 * @method static \Database\Factories\ManagementReviewDelegationFactory factory($count = null, $state = [])
 * @method static Builder<static>|ManagementReviewDelegation newModelQuery()
 * @method static Builder<static>|ManagementReviewDelegation newQuery()
 * @method static Builder<static>|ManagementReviewDelegation query()
 * @method static Builder<static>|ManagementReviewDelegation whereComment($value)
 * @method static Builder<static>|ManagementReviewDelegation whereCreatedAt($value)
 * @method static Builder<static>|ManagementReviewDelegation whereDelegateUserId($value)
 * @method static Builder<static>|ManagementReviewDelegation whereEndedEarlyAt($value)
 * @method static Builder<static>|ManagementReviewDelegation whereEndsAt($value)
 * @method static Builder<static>|ManagementReviewDelegation whereId($value)
 * @method static Builder<static>|ManagementReviewDelegation whereStartsAt($value)
 * @method static Builder<static>|ManagementReviewDelegation whereUpdatedAt($value)
 * @method static Builder<static>|ManagementReviewDelegation whereUserId($value)
 *
 * @mixin \Eloquent
 */
#[Guarded([
    'id',
    'created_at',
    'updated_at',
])]
class ManagementReviewDelegation extends Model
{
    /** @use HasFactory<ManagementReviewDelegationFactory> */
    use HasFactory;

    use LogsActivity;

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'ended_early_at' => 'datetime',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function delegate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delegate_user_id');
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where(function (Builder $q): void {
            $q->whereNull('starts_at')
                ->orWhere('starts_at', '<=', now());
        })
            ->where('ends_at', '>', now())
            ->whereNull('ended_early_at');
    }

    public function isActive(): bool
    {
        return ($this->starts_at === null || $this->starts_at->lte(now()))
            && $this->ends_at->gt(now())
            && $this->ended_early_at === null;
    }

    public function isScheduled(): bool
    {
        return $this->starts_at !== null
            && $this->starts_at->gt(now())
            && $this->ended_early_at === null;
    }

    public function getComment(): string
    {
        return $this->comment ?? __('delegation.default_comment');
    }
}
