<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\LocaleTrait;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class NewPasswordController extends Controller
{
    use LocaleTrait;

    /**
     * Handle an incoming new password request.
     *
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $this->setLocaleFromRequest($request);

        // log password reset attempt
        activity()
            ->causedBy($request->user())
            ->withProperties([
                'email' => $request->email,
                'ip' => $request->ip(),
            ])
            ->log('password.reset.attempt');

        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::min(config('auth.password_min_length'))->uncompromised()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                    'new_password_required' => false,
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        // log password reset success
        activity()
            ->withProperties([
                'email' => $request->email,
                'ip' => $request->ip(),
            ])
            ->log('password.reset.success');

        return response()->json(['status' => __($status)]);
    }
}
