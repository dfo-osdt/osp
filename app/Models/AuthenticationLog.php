<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $authenticatable_type
 * @property int $authenticatable_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property Carbon|null $login_at
 * @property bool $login_successful
 * @property Carbon|null $logout_at
 * @property bool $cleared_by_user
 *
 * @method static Builder<static> successful()
 *
 * @property-read Model $authenticatable
 *
 * @method static Builder<static>|AuthenticationLog newModelQuery()
 * @method static Builder<static>|AuthenticationLog newQuery()
 * @method static Builder<static>|AuthenticationLog query()
 * @method static Builder<static>|AuthenticationLog whereAuthenticatableId($value)
 * @method static Builder<static>|AuthenticationLog whereAuthenticatableType($value)
 * @method static Builder<static>|AuthenticationLog whereClearedByUser($value)
 * @method static Builder<static>|AuthenticationLog whereId($value)
 * @method static Builder<static>|AuthenticationLog whereIpAddress($value)
 * @method static Builder<static>|AuthenticationLog whereLoginAt($value)
 * @method static Builder<static>|AuthenticationLog whereLoginSuccessful($value)
 * @method static Builder<static>|AuthenticationLog whereLogoutAt($value)
 * @method static Builder<static>|AuthenticationLog whereUserAgent($value)
 *
 * @mixin \Eloquent
 */
#[Fillable([
    'ip_address',
    'user_agent',
    'login_at',
    'login_successful',
    'logout_at',
    'cleared_by_user',
])]
#[Table(name: 'authentication_log')]
#[WithoutTimestamps]
class AuthenticationLog extends Model
{
    protected $casts = [
        'login_at' => 'datetime',
        'logout_at' => 'datetime',
        'login_successful' => 'boolean',
        'cleared_by_user' => 'boolean',
    ];

    /**
     * @return MorphTo<Model, $this>
     */
    public function authenticatable(): MorphTo
    {
        return $this->morphTo();
    }

    #[Scope]
    protected function successful(Builder $query): Builder
    {
        return $query->where('login_successful', true);
    }
}
