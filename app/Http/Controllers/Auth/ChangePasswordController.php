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
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $request->updatePassword();

        return response()->json(['status' => __('passwords.changed')]);
    }
}
