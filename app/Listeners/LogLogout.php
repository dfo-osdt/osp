<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;

class LogLogout
{
    public function handle(Logout $event): void
    {
        $latest = $event->user->authentications()
            ->whereNull('logout_at')
            ->latest('login_at')
            ->first();

        $latest?->update(['logout_at' => now()]);
    }
}
