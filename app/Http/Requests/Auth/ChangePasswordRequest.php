<?php

namespace App\Http\Requests\Auth;

use App\Events\Auth\PasswordChanged;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'current_password' => ['current_password'],
            'password' => ['required', 'confirmed', Password::min(config('auth.password_min_length'))->uncompromised()],
        ];
    }

    /**
     * Update the password of the user
     */
    public function updatePassword(): void
    {
        $this->user()->forceFill([
            'password' => Hash::make($this->password),
            'remember_token' => Str::random(60),
            'new_password_required' => false,
        ])->save();

        event(new PasswordChanged($this->user()));
    }
}
