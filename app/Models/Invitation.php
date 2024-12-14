<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Str;
use URL;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $invitation_token
 * @property int $user_id
 * @property int|null $invited_by
 * @property \Illuminate\Support\Carbon|null $registered_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User|null $invitedByUser
 * @property-read \App\Models\User $user
 *
 * @method static \Database\Factories\InvitationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invitation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invitation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invitation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invitation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invitation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invitation whereInvitationToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invitation whereInvitedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invitation whereRegisteredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invitation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invitation whereUserId($value)
 *
 * @mixin \Eloquent
 */
class Invitation extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'invitation_token',
        'invited_by',
        'registered_at',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
    ];

    protected $hidden = [
        'invitation_token',
    ];

    // logging options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }

    // relationships

    /**
     * Get the user that owns the invitation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user that invited the user.
     */
    public function invitedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    /** Generate signed link for this invitation */
    public function getSignedLink(): string
    {
        return URL::temporarySignedRoute(
            'invitation.verify',
            now()->addDays(config('auth.invitation.expire_days', 5)),
            [
                'id' => $this->user->id,
                'hash' => sha1($this->invitation_token),
            ]);
    }

    /**
     * Generate a new invitation token.
     */
    public static function generateInvitationToken(): string
    {
        return Str::random(40);
    }
}
