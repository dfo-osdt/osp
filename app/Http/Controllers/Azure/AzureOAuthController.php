<?php

namespace App\Http\Controllers\Azure;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class AzureOAuthController extends Controller
{
    public function redirect()
    {

        return Socialite::driver('azure')->redirect();

    }

    public function callback()
    {
        $user = Socialite::driver('azure')->user();

        // possible scenarios:
        //  1. user already exists in the system
        //  2. user does not exist in the system
        //  3. user exists in the system but has only been invited
        //  4. user exists in the system but has not verified their email

        dd($user);

        $user->token;
    }
}
