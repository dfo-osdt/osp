<?php

namespace App\Filament\Requests\Auth;

use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Facades\Filament;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Models\Contracts\FilamentUser;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Login as BaseAuth;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\SimplePage;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends BaseAuth
{
    use WithRateLimiting;

    public function authenticate(): ?LoginResponse
    {

	/**
	 * Check if user is rate limited. Log attempt if user is rate limited.
	 *
	 * @throws DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException
	 */
	$maxAttempts = Config::get('auth.rate_limit.max_attempts');
	$decaySeconds = Config::get('auth.rate_limit.decay_seconds');
	try {
	    $this->rateLimit($maxAttempts, $decaySeconds);
	} catch (TooManyRequestsException $exception) {
	    $this->logLockout();
	    $this->getRateLimitedNotification($exception)?->send();

	    return null;
	}

        $data = $this->form->getState();

	/**
	 * Attempt to authenticate the request's credentials.
	 *
	 * @throws \Illuminate\Validation\ValidationException
	 */
        if (! Filament::auth()->attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
	    $this->throwFailureValidationException();
        }

        $user = Filament::auth()->user();

	/**
	 * Check if user's email is verified
	 *
	 * @throws \Illuminate\Validation\ValidationException
	 */
	if ($user && ! $user->hasVerifiedEmail()) {
	    Filament::auth()->logout();
	    $this->throwFailureValidationException();
	}

	/**
	 * Check if user has permission to access admin panel
	 *
	 * @throws \Illuminate\Validation\ValidationException
	 */
        if (
	    ($user instanceof FilamentUser) &&
	    (! $user->canAccessPanel(Filament::getCurrentPanel()))
        ) {
	    Filament::auth()->logout();

	    $this->throwFailureValidationException();
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }

    /**
     * Get the rate limiting throttle key for the request
     *
     * @return string
     */
    public function throttleKey()
    {

	return Str::lower($this->data['email']).'|'.request()->ip();
    }

    /**
     * Log lockout but rate limit to one entry per 2 minutes
     * as we don't want to log all failed attempts in the
     * log. This could be asbused to fill up the logs.
     */
    public function logLockout()
    {
	RateLimiter::attempt(
	    $this->throttleKey().'|lockout',
	    Config::get('auth.rate_limit.max_attempts'),
	    function () {
		activity()->withProperties([
		    'ip' => request()->ip(),
		    'email' => $this->data['email'],
		])->log('auth.lockout');
	    },
	    120
	);
    }

    /**
     * Overload getForms() to remove 'Remember Me' checkbox.
     *
     * @return array
     */
    protected function getForms(): array
    {
	return [
	    'form' => $this->form(
		$this->makeForm()
		     ->schema([
			 $this->getEmailFormComponent(),
			 $this->getPasswordFormComponent(),
		     ])
		     ->statePath('data'),
	    ),
	];
    }
}
