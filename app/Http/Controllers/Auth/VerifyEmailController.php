<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $user = User::findOrFail($request->route('id'));
        } catch (\Exception $e) {
            return redirect()->intended(
                config('app.frontend_url').'#/invalid-signature'
            );
        }

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
