<?php

namespace App\Actions\Accounts;

use App\DTOs\RegisterUserData;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RegisterUser
{
    public static function register(RegisterUserData $data): User
    {
        // check if the user already exists
        $user = \App\Models\User::query()->where('email', $data->email)->first();
        if ($user) {
            // User exits and does not have an invitation or is active (has registered)
            if ($user->active) {
                throw ValidationException::withMessages(
                    ['account' => __('Problem with registration, please contact support'),
                    ]);
            }

            // User exists but is not active (has not registered), so update the user
            // with the new password.
            $user->password = Hash::make($data->password);
            $user->save();

        } else {
            $user = \App\Models\User::query()->create([
                'first_name' => $data->first_name,
                'last_name' => $data->last_name,
                'email' => $data->email,
                'password' => $data->password ? Hash::make($data->password) : null,
                'locale' => $data->locale,
            ]);
        }

        $user->email_verification_token = User::generateEmailVerificationToken();
        $user->save();
        $user->associateAuthor();

        // give the user the author role - this is the default role
        $user->assignRole('author');

        return $user->refresh();
    }

    /**
     * From Azure Entra - here email is considered verified and
     * the user is active.
     */
    public static function registerAzureUser(RegisterUserData $data): User
    {
        $user = self::register($data);
        $user->markEmailAsVerified();

        return $user->refresh();
    }
}
