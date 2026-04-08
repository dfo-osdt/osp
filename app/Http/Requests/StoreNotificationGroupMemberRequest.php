<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNotificationGroupMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'member_user_id' => [
                'required',
                'exists:users,id',
                Rule::notIn([$this->user()->id]),
            ],
            'expires_at' => ['nullable', 'date', 'after:now'],
        ];
    }
}
