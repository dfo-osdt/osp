<?php

namespace App\Listeners;

use App\Models\AuthenticationLog;
use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    public function handle(Login $event): void
    {
        $event->user->authentications()->create([
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'login_at' => now(),
            'login_successful' => true,
        ]);
    }
}
