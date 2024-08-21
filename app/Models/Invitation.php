<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Str;
use URL;

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
