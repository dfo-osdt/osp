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

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(config('auth.password_min_length'))->uncompromised()],
            'locale' => ['string', 'max:2', 'in:en,fr'],
        ]);

        // check if the user already exists
        $user = User::where('email', $validated['email'])->first();
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
            $user->password = Hash::make($validated['password']);
            $user->active = true;
            $user->save();
        } else {
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'locale' => $validated['locale'] ?? 'en',
            ]);
        }

        $user->email_verification_token = User::generateEmailVerificationToken();
        $user->save();
        $user->refresh();
        $user->associateAuthor();

        // give the user the author role - this is the default role
        $user->assignRole('author');

        event(new Registered($user->refresh()));

        return response()->json([
            'message' => 'registered',
            'email' => $user->email,
        ]);
    }
}
