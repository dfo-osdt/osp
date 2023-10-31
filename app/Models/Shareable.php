<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Shareable extends Model
{

    use HasUlids;
    use HasFactory;

    public $casts = [
        'expires_at' => 'datetime',
        'can_edit' => 'boolean',
        'can_delete' => 'boolean',
    ];

    public $protected = [
        'id',
        'created_at',
        'updated_at',
        'user_id',
        'shared_by',
        'shareable_type',
        'shareable_id',
    ];

    protected $fillable = [
        'expires_at',
        'can_edit',
        'can_delete',
    ];

    /**
     * User with whom the shareable is shared.
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * User who shared the shareable.
     * @return BelongsTo
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
        return !$this->isExpired();
    }

    public function isEditable(): bool
    {
        return $this->can_edit && !$this->isExpired();
    }

    public function isDeletable(): bool
    {
        return $this->can_delete && !$this->isExpired();
    }
}
