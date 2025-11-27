<?php

namespace App\Models;

use App\Enums\Permissions\UserPermission;
use App\Enums\Permissions\UserRole;
use App\Notifications\Authentication\PasswordResetNotification;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Rappasoft\LaravelAuthenticationLog\Traits\AuthenticationLoggable;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $email_verification_token
 * @property string|null $password
 * @property string $locale
 * @property string|null $remember_token
 * @property bool $active
 * @property bool $new_password_required
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $actions
 * @property-read int|null $actions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog> $authentications
 * @property-read int|null $authentications_count
 * @property-read \App\Models\Author|null $author
 * @property-read string $full_name
 * @property-read \App\Models\Invitation|null $invitation
 * @property-read \Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog|null $latestAuthentication
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Invitation> $sentInvitations
 * @property-read int|null $sent_invitations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Shareable> $sharedWith
 * @property-read int|null $shared_with_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 *
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerificationToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereNewPasswordRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 *
 * @mixin \Eloquent
 */
class User extends Authenticatable implements FilamentUser, HasLocalePreference, HasName, MustVerifyEmail
{
    use AuthenticationLoggable;
    use CausesActivity;
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'locale',
    ];

    /**
     * The attributes that should be hidden for serialization.
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
        'new_password_required' => 'boolean',
    ];

    /**
     * Make sure that the email is always stored as lowercase to prevent duplicates.
     */
    protected function email(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value,
            set: fn ($value) => strtolower((string) $value),
        );
    }

    /**
     * Get the full name of the user.
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
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<\App\Models\Invitation, $this>
     */
    public function invitation(): HasOne
    {
        return $this->hasOne(Invitation::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Shareable, $this>
     */
    public function sharedWith(): HasMany
    {
        return $this->hasMany(Shareable::class, 'user_id');
    }

    /**
     * Get the invitations sent by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Invitation, $this>
     */
    public function sentInvitations(): HasMany
    {
        return $this->hasMany(Invitation::class, 'invited_by', 'id')->with('user');
    }

    /**
     * All users should have an author profile created for them
     * upon registration.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<\App\Models\Author, $this>
     */
    public function author(): HasOne
    {
        return $this->hasOne(Author::class);
    }

    /**
     * Associate or create an author profile for the user. This function
     * checks if an author profile with the same email already exists
     * in the database. If it does, it will associate the user with that
     * author profile. If it does not, it will create a new author profile
     * based on the user's information and associate the user with that.
     */
    public function associateAuthor(): Author
    {
        // if user has already been associated with an author, return that author
        if ($this->author) {
            return $this->author;
        }

        $author = Author::where('email', $this->email)->first();

        if ($author) {
            $this->author()->save($author);
        } else {
            $author = Author::create([
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'organization_id' => Organization::getDefaultOrganization()->id,
            ]);

            $this->author()->save($author);
        }

        $this->save();

        return $author;
    }

    /**
     * Override the default verification email to use a random token instead.
     * We are doing this because we do not allow login until the user has
     * verified their email address and hashing the email address can be
     * used to circumvent this verification process if app key compromised.
     *
     * @override MustVerifyEmail
     */
    public function getEmailForVerification(): ?string
    {
        return $this->email_verification_token;
    }

    /**
     * Generate a new email verification token.
     */
    public static function generateEmailVerificationToken(): string
    {
        return Str::random(40);
    }

    /**
     * Mark the given user's email as verified. This also activates the user so
     * that they can login for the first time.
     *
     * @override MustVerifyEmail
     */
    public function markEmailAsVerified(): bool
    {
        if ($this->invitation) {
            $this->invitation->registered_at = now();
            $this->invitation->save();
        }

        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
            'active' => true,
        ])->save();
    }

    /**
     * Determine if the user can access the Filament admin panel.
     * This function checks if the user has the 'admin' role, which
     * is required to grant access to Filament. Users without this
     * role will be denied access.
     *
     * @return bool True if the user has the 'admin' role, false otherwise.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->can(UserPermission::VIEW_LIBRARIUM);
    }

    /**
     * Get the display name for the user in the Filament admin panel.
     * This function concatenates the user's first name and last name
     * to form a full name that will be used as the user's display name
     * within Filament.
     *
     * @return string The user's full name in "First Last" format.
     */
    public function getFilamentName(): string
    {
        return $this->getFullNameAttribute();
    }

    /**
     * Get the preferred locale of the user.
     */
    public function preferredLocale(): string
    {
        return $this->locale;
    }

    /**
     * Override the default reset email
     */
    public function sendPasswordResetNotification($token): void
    {

        $this->notify(new PasswordResetNotification($this, $token));
    }

    public function previousSuccessfulLoginAt()
    {
        return $this->authentications()->whereLoginSuccessful(true)->skip(1)->first()?->login_at;
    }

    /** Is this a Regional Editor */
    public function isRegionalEditor(): bool
    {
        $regionalEditorRoles = UserRole::getRegionalEditorRoles();

        return $this->hasAnyRole($regionalEditorRoles);
    }
}
