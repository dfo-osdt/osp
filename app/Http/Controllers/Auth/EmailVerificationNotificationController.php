<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email', 'exists:users,email'],
        ]);

        // get the user
        $user = User::where('email', $validated['email'])->first();

        if ($user->hasVerifiedEmail()) {
            return response()->json(['status' => 'already-verified']);
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['status' => 'verification-link-sent']);
    }
}
