<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
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

	/**
	 * Register the Librarium View Panel gate.
	 *
	 * This gate determines who can view Librarium in non-local environments.
	 */
	Gate::define('viewLibrarium', function($user) {
	    return $user->can('view_librarium');
	});

	/**
	 * Register the User Update gate.
	 *
	 * This gate determines who can update Users in Librarium in non-local environments.
	 */
	Gate::define('updateAnyUser', function($user) {
	    return $user->can('update_any_user');
	});
    }
}
