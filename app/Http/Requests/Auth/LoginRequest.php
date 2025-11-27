<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Container\Attributes\Config;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function __construct(
        #[Config('auth.rate_limit.max_attempts', 5)] protected int $maxAttempts,
        #[Config('auth.rate_limit.decay_seconds', 600)] protected int $decaySeconds
    ) {}

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $credentials = $this->only('email', 'password');
        // user must be active
        $credentials['active'] = true;

        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey(), $this->decaySeconds);

            // check if this user has verified their email address
            $user = \App\Models\User::query()->where('email', $this->email)->first();
            if ($user && ! $user->hasVerifiedEmail()) {
                throw ValidationException::withMessages([
                    'email' => __('auth.failed_email_not_verified'),
                ]);
            }

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), $this->maxAttempts)) {
            return;
        }

        event(new Lockout($this));
        $this->logLockout();

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::lower($this->input('email')).'|'.$this->ip();
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
                    'ip' => $this->ip(),
                    'email' => $this->email,
                ])->log('auth.lockout');
            },
            120
        );
    }
}
