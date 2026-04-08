<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Request;

class LogLogout
{
    public function handle(Logout $event): void
    {
        $latest = $event->user->authentications()
            ->whereNull('logout_at')
            ->where('login_successful', true)
            ->where('ip_address', Request::ip())
            ->where('user_agent', Request::userAgent())
            ->latest('login_at')
            ->first();

        $latest?->update(['logout_at' => now()]);
    }
}
