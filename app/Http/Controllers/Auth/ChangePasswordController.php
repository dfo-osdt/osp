<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;

class ChangePasswordController extends Controller
{
    /**
     * Change the password of the user
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $request->updatePassword();

        return ['status' => __('passwords.changed')];
    }
}
