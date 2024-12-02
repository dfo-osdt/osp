<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\AuthorizedEmailDomain;
use App\Traits\LocaleTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

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

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['bail', 'required', 'string', 'email', 'max:255', new AuthorizedEmailDomain],
            'password' => ['required', 'confirmed', Password::min(config('auth.password_min_length'))->uncompromised()],
            'locale' => ['string', 'max:2', 'in:en,fr'],
        ]);

        // request email to lowercase - ensure no duplicate emails
        $validated['email'] = strtolower($validated['email']);

        // check if the user already exists
        $user = User::where('email', $validated['email'])->first();
        if ($user) {
            // User exits and does not have an invitation or is active (has registered)
            if ($user->active) {
                throw ValidationException::withMessages(
                    ['account' => __('Problem with registration, please contact support'),
                    ]);
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
