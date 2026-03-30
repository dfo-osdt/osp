<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property string $authenticatable_type
 * @property int $authenticatable_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property \Illuminate\Support\Carbon|null $login_at
 * @property bool $login_successful
 * @property \Illuminate\Support\Carbon|null $logout_at
 * @property bool $cleared_by_user
 *
 * @method static Builder<static> successful()
 */
class AuthenticationLog extends Model
{
    public $timestamps = false;

    protected $table = 'authentication_log';

    protected $fillable = [
        'ip_address',
        'user_agent',
        'login_at',
        'login_successful',
        'logout_at',
        'cleared_by_user',
    ];

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

    public function scopeSuccessful(Builder $query): Builder
    {
        return $query->where('login_successful', true);
    }
}
