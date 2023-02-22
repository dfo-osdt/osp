<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     */
    public function __invoke(Request $request): \Illuminate\Http\RedirectResponse
    {
        // if user does not exist redirect to invalid signature
        if (! User::exists('id', $request->route('id'))) {
            return redirect()->intended(
                config('app.frontend_url').'#/invalid-signature'
            );
        }

        // get user
        $user = User::findOrFail($request->route('id'));

        if (! hash_equals(sha1($user->getEmailForVerification()), (string) $request->route('hash'))) {
            return redirect()->intended(
                config('app.frontend_url').'#/invalid-signature'
            );
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->intended(
            config('app.frontend_url').'#/auth/login?verified=1&email='.$user->email
        );
    }
}
