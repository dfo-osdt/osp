<?php

namespace App\Http\Controllers\Auth;

use App\Events\Auth\Invited;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Invitation;
use App\Models\User;
use Hash;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Str;

class InvitedUserController extends Controller
{
    public function invite(Request $request): UserResource
    {
        // validate the request
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'bail|required|email|unique:users,email',
            'locale' => 'string|in:en,fr',
        ]);

        // generate random 20 char password string
        $password = Str::password(20);

        // create a new user
        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'locale' => $validated['locale'] ?? 'en',
            'password' => Hash::make($password),

        ]);

        $user->new_password_required = true;
        $user->associateAuthor();
        $user->assignRole('author');
        $user->save();

        // create the invitation
        $invitation = $user->invitation()->create([
            'invitation_token' => Invitation::generateInvitationToken(),
            'invited_by' => $request->user()->id,
        ]);

        event(new Invited($invitation, $password));

        return UserResource::make($user);
    }

    public function accept($id, $hash): \Illuminate\Http\RedirectResponse
    {
        $user = User::find($id);
        if (! $user) {
            return redirect()->intended(
                config('app.frontend_url').'#/invalid-signature'
            );
        }

        if (! hash_equals(sha1($user->invitation->invitation_token), (string) $hash)) {
            return redirect()->intended(
                config('app.frontend_url').'#/invalid-signature'
            );
        }

        // make sure the user is not already verified (they could have registered)
        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(
                config('app.frontend_url').'#/auth/login?verified=1&email='.$user->email
            );
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        // redirect to the login page
        return redirect()->intended(
            config('app.frontend_url').'#/auth/login?verified=1&email='.$user->email
        );
    }
}
