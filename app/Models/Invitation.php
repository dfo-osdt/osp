<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;
use URL;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'invitation_token',
        'invited_by',
        'registered_at',
    ];

    // relationships

    /**
     * Get the user that owns the invitation.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user that invited the user.
     */
    public function invitedBy()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    /** Generate signed link for this invitation */
    public function getSignedLink(): string
    {
        return URL::temporarySignedRoute(
            'invitation.verify',
            now()->addDays(2),
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
