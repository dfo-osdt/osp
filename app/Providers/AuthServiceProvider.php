<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {

        VerifyEmail::toMailUsing(fn (User $notifiable, string $url) => (new MailMessage)->subject(__('email.auth.verify.title'))->markdown('authentication.verify_email', ['url' => $url, 'user' => $notifiable->first_name]));

    }
}
