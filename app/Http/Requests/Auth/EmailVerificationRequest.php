<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class EmailVerificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // user exists with key
        if (! User::exists('id', $this->route('id'))) {
            return false;
        }

        // get user
        $user = User::findOrFail($this->route('id'));

        if (! hash_equals(sha1($user->getEmailForVerification()), (string) $this->route('hash'))) {
            return false;
        }

        return true;
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): Validator
    {
        return $validator;
    }
}
