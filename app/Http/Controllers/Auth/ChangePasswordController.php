<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use Illuminate\Http\JsonResponse;

class ChangePasswordController extends Controller
{
    /**
     * Change the password of the user
     */
    public function __invoke(ChangePasswordRequest $request): JsonResponse
    {
        $request->updatePassword();

        // log password change
        activity()
            ->causedBy($request->user())
            ->withProperties([
                'email' => $request->user()->email,
                'ip' => $request->ip(),
            ])
            ->log('password.changed');

        return response()->json(['status' => __('passwords.changed')]);
    }
}
