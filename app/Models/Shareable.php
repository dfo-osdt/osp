<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $user_id
 * @property int|null $shared_by
 * @property string $shareable_type
 * @property int $shareable_id
 * @property bool $can_edit
 * @property bool $can_delete
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property-read Model|\Eloquent $shareable
 * @property-read \App\Models\User|null $sharingUser
 * @property-read \App\Models\User $user
 *
 * @method static \Database\Factories\ShareableFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shareable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shareable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shareable query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shareable whereCanDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shareable whereCanEdit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shareable whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shareable whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shareable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shareable whereShareableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shareable whereShareableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shareable whereSharedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shareable whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shareable whereUserId($value)
 *
 * @mixin \Eloquent
 */
class Shareable extends Model
{
    use HasFactory;
    use HasUlids;

    public $casts = [
        'expires_at' => 'datetime',
        'can_edit' => 'boolean',
        'can_delete' => 'boolean',
    ];

    public $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * User with whom the shareable is shared.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * User who shared the shareable.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, $this>
     */
    public function sharingUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'shared_by');
    }

    /** Item shared */
    public function shareable(): MorphTo
    {
        return $this->morphTo();
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isViewable(): bool
    {
        return ! $this->isExpired();
    }

    public function isEditable(): bool
    {
        return $this->can_edit && ! $this->isExpired();
    }

    public function isDeletable(): bool
    {
        return $this->can_delete && ! $this->isExpired();
    }
}
