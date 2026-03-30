<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Failed;

class LogFailedLogin
{
    public function handle(Failed $event): void
    {
        if (! $event->user instanceof User) {
            return;
        }

        $event->user->authentications()->create([
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'login_at' => now(),
            'login_successful' => false,
        ]);
    }
}
