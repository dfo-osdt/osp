<?php

namespace App\Filament\Requests\Auth;

use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Pages\Login;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends Login
{
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

        $user = \App\Models\User::query()->findOrFail(Filament::auth()->user()->id);

        /**
         * Check if user has permission to access admin panel
         *
         * @throws ValidationException
         */
        if (
            (! $user->canAccessPanel(Filament::getCurrentOrDefaultPanel())) ||
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
     *
     * @throws TooManyRequestsException
     */
    public function ensureIsNotRateLimited(): void
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
     */
    public function throttleKey(): string
    {
        return Str::lower($this->data['email']).'|'.request()->ip();
    }

    /**
     * Log lockout but rate limit to one entry per 2 minutes
     * as we don't want to log all failed attempts in the
     * log. This could be asbused to fill up the logs.
     */
    public function logLockout(): void
    {
        RateLimiter::attempt(
            $this->throttleKey().'|lockout',
            1,
            function (): void {
                activity()->withProperties([
                    'ip' => request()->ip(),
                    'email' => $this->data['email'],
                ])->log('auth.lockout-librarium');
            },
            120
        );
    }
}
