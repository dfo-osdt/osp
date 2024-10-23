<?php

namespace App\Filament\Requests\Auth;

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Pages\Auth\Login as BaseAuth;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

use Illuminate\Validation\ValidationException;


class LoginRequest extends BaseAuth
{

    /**
     * Override the rateLimit method to allow configurable maxAttempts and decaySeconds
     * for login throttling. This method pulls rate-limiting values from the
     * configuration file ('filament.rate_limit') and applies them to the Filament
     * login process.
     *
     * @param int $maxAttempts      The maximum number of login attempts before the user is throttled.
     * @param int $decaySeconds     The number of seconds the user is locked out after exceeding max attempts.
     * @param string|null $method   The optional method used to differentiate between rate-limiting contexts.
     * @param string|null $component The optional Filament component for which rate-limiting is being applied.
     *
     * @return mixed
     */
    protected function rateLimit($maxAttempts, $decaySeconds = 60, $method = null, $component = null)
    {
        // Fetch the maxAttempts and decaySeconds from config
        $maxAttempts = Config::get('filament.rate_limit.max_attempts', $maxAttempts);
        $decaySeconds = Config::get('filament.rate_limit.decay_seconds', $decaySeconds);

        // Call the parent rateLimit method with the new values
        return parent::rateLimit($maxAttempts, $decaySeconds, $method, $component);
    }
    
    public function rules()
    {
	return [
	    'email' => ['required', 'string', 'email'],
	    'password' => ['required', 'string'],
	];
    }

    public function authenticate(): ?LoginResponse
    {
	//	$data = $this->form->getState()
	$this -> validateRequest();
	
	return parent::authenticate();
    }

    public function validateRequest()
    {
	
	/* $data = Arr::only($this->data, ['email','password']);
	   dd($this);
	   $credentials = $this->validate([
	   'email' => ['required', 'string', 'email'],
	   'password' => ['required', 'string'],
	   ], $this->data); */
	$credentials = $this->form->getState();
//	   dd($this->form->getState());
	// User must be active
	$credentials['active'] = true;

	if (! Filament::auth()->attempt($credentials, $credentials['remember'] ?? false)) {
	    $user = User::where('email', $credentials['email'])->first();
	    if ($user && ! $user->hasVerifiedEmail()) {
		throw ValidationException::withMessages([
		    'email' => __('auth.failed_email_not_verified'),
		]);
	    }
	    throw ValidationException::withMessages([
		'email' => __('auth.failed'),
	    ]);
	}
	return;
    }
}
