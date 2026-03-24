<?php

namespace App\Models;

use Database\Factories\ManagementReviewDelegationFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

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
 */
class ManagementReviewDelegation extends Model
{
    /** @use HasFactory<ManagementReviewDelegationFactory> */
    use HasFactory;

    use LogsActivity;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

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
