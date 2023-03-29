<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\LocaleTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    use LocaleTrait;

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $this->setLocaleFromRequest($request);

        // request email to lowercase - ensure no duplicate emails
        $request->merge(['email' => strtolower($request->email)]);

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(config('auth.password_min_length'))->uncompromised()],
        ]);

        // check if the user already exists
        $user = User::where('email', $request->email)->first();
        if ($user) {
            // User exits and does not have an invitation or is active (has registered)
            if ($user->active) {
                $request->validate([
                    'email' => ['unique:users'],
                ]);
                throw new \Exception('User already exists');
            }

            // User exists but is not active (has not registered), so update the user
            // with the new password.
            $user->password = Hash::make($request->password);
            $user->active = true;
            $user->save();
        } else {
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
        }

        $user->email_verification_token = User::generateEmailVerificationToken();
        $user->associateAuthor();
        $user->save();

        // give the user the author role - this is the default role
        $user->assignRole('author');

        event(new Registered($user->refresh()));

        return response()->json([
            'message' => 'registered',
            'email' => $user->email,
        ]);
    }
}
