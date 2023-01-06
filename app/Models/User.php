<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Traits\HasRoles;
use Str;

class User extends Authenticatable implements MustVerifyEmail, Auditable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use \OwenIt\Auditing\Auditable;
    use HasRoles;

    // Audit Thresholds - amount of audit records to keep
    protected $auditThreshold = 50;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'email_verified_at',
        'email_verification_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'active' => 'boolean',
    ];

    /**
     * Make sure that the email is always stored as lowercase to prevent duplicates.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function email(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value,
            set: fn ($value) => strtolower($value),
        );
    }

    /**
     * Get the full name of the user.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name.' '.$this->last_name;
    }

    // relationships

    /**
     * Get the invitation record associated with the user. Only users
     * that were invited to join the application will have one.
     * This record will not be erased when the user joins.
     */
    public function invitation(): HasOne
    {
        return $this->hasOne(Invitation::class);
    }

    /**
     * All users should have an author profile created for them
     * upon registration.
     */
    public function author(): HasOne
    {
        return $this->hasOne(Author::class);
    }

    /**
     * Override the default verification email to use a random token instead.
     * We are doing this because we do not allow login until the user has
     * verified their email address and hashing the email address can be
     * used to circumvent this verification process.
     *
     * @override MustVerifyEmail
     */
    public function getEmailForVerification(): string|null
    {
        return $this->email_verification_token;
    }

    /**
     * Generate a new email verification token.
     */
    public static function generateEmailVerificationToken(): string
    {
        return hash_hmac('sha256', Str::random(40), config('app.key'));
    }

    /**
     * Mark the given user's email as verified. This also activates the user so
     * that they can login for the first time.
     *
     * @override MustVerifyEmail
     */
    public function markEmailAsVerified(): bool
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
            'email_verification_token' => null,
            'active' => true,
        ])->save();
    }
}
