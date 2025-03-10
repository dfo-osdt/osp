<?php

namespace App\Http\Controllers\Azure;

use App\Actions\Accounts\RegisterUser;
use App\DTOs\RegisterUserData;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AzureOAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {

        return Socialite::driver('azure')->redirect();

    }

    public function callback(Request $request): RedirectResponse
    {

        // is user aleady logged in?
        // this happens when a user uses the back button after logging in
        if (Auth::check()) {
            return redirect()->intended('/#/auth/login');
        }

        // handle possible errors
        if ($request->has('error')) {
            $errorDescription = urlencode($request->query('error_description') ?? 'Unknown error');

            activity()
                ->withProperties([
                    'ip' => request()->ip(),
                    'error' => $request->query('error'),
                    'error_description' => $errorDescription,
                ])
                ->log('Azure OAuth error');

            return redirect("/#/auth/login?error=oauth_error&error_description=$errorDescription");
        }

        $azureUser = Socialite::driver('azure')->user();

        $email = strtolower($azureUser->getEmail());

        $user = User::where('email', $email)->first();

        if ($user) {
            if (! $user->active) {

                // if email is already verified but user is not active,
                // then this user is effectively banned from the system
                // as this state can only be set by an admin.
                if ($user->hasVerifiedEmail()) {
                    throw ValidationException::withMessages(
                        ['account' => __('Problem with registration, please contact support'),
                        ]);
                }

                // else user is not active and email is not verified, has
                // likely been invited.
                $user->password = null;
                $user->markEmailAsVerified();
                $user->save();
            }
        } else {

            // User does not exist in the system, let's create them.
            $givenName = $azureUser->user['givenName'] ?? Str::of($azureUser->getName())->before(' ');
            $surname = $azureUser->user['surname'] ?? Str::of($azureUser->getName())->after(' ');
            $preferredLanguage = ($azureUser->user['preferredLanguage'] ?? 'en') == 'fr-CA' ? 'fr' : 'en';

            $userData = new RegisterUserData(
                $givenName,
                $surname,
                $email,
                null,
                $preferredLanguage
            );

            $user = RegisterUser::registerAzureUser($userData);

        }

        // log oauth user attempt
        activity()
            ->causedBy($user)
            ->withProperties([
                'ip' => request()->ip(),
                'azure_user_id' => $azureUser->getId(),
                'azure_user_email' => $azureUser->getEmail(),
            ])
            ->log('Azure OAuth login');

        // login the user
        Auth::login($user);

        return redirect()->intended('/#/auth/login?callback=true');

    }
}
