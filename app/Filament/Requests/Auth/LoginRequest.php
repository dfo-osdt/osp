<?php

namespace App\Filament\Requests\Auth;

use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Models\Contracts\FilamentUser;
use Filament\Pages\Auth\Login as BaseAuth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginRequest extends BaseAuth
{
    use WithRateLimiting;

    protected int $maxAttempts;

    protected int $decaySeconds;

    public function __construct()
    {
        $this->maxAttempts = Config::get('auth.rate_limit.max_attempts', 5);
        $this->decaySeconds = Config::get('auth.rate_limit.decay_seconds', 600);
    }

    public function authenticate(): ?LoginResponse
    {

        try {
            $this->ensureIsNotRateLimited();
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $data = $this->form->getState();

        if (! Filament::auth()->attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
            RateLimiter::hit($this->throttleKey(), $this->decaySeconds);
            $this->throwFailureValidationException();
        }

        $user = User::findOrFail(Filament::auth()->user()->id);

        /**
         * Check if user has permission to access admin panel
         *
         * @throws \Illuminate\Validation\ValidationException
         */
        if (
            (($user instanceof FilamentUser) && (! $user->canAccessPanel(Filament::getCurrentPanel()))) ||
            (! $user->hasVerifiedEmail()) ||
            (! $user->active)
        ) {
            Filament::auth()->logout();
            $this->throwFailureValidationException();
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), $this->maxAttempts)) {
            return;
        }

        $this->logLockout();

        $component = static::class;
		$method = __FUNCTION__;
        $seconds = RateLimiter::availableIn($this->throttleKey());
        $ip = request()->ip();

        throw new TooManyRequestsException(
            $component,
            $method,
            $ip,
            $seconds
        );
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
            1,
            function () {
                activity()->withProperties([
                    'ip' => request()->ip(),
                    'email' => $this->data['email'],
                ])->log('auth.lockout-librarium');
            },
            120
        );
    }

    /**
     * Overload getForms() to remove 'Remember Me' checkbox.
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
