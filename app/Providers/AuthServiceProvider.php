<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\MediaPolicy;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     */
    protected $policies = [
        Media::class => MediaPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        VerifyEmail::toMailUsing(function (User $notifiable, string $url) {
            return (new MailMessage)->subject(__('email.auth.verify.title'))->markdown('authentication.verify_email', ['url' => $url, 'user' => $notifiable->first_name]);
        });

    }
}
