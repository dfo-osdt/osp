<?php

namespace App\Http\Requests;

use App\Models\ManagementReviewDelegation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreManagementReviewDelegationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return ! $this->user()->isActingAsDelegate();
    }

    public function rules(): array
    {
        return [
            'delegate_user_id' => [
                'required',
                'exists:users,id',
                Rule::notIn([$this->user()->id]),
            ],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['required', 'date', $this->input('starts_at') ? 'after:starts_at' : 'after:now'],
            'comment' => ['nullable', 'string'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $startsAt = $this->input('starts_at') ?? now();
            $endsAt = $this->input('ends_at');

            // Two ranges overlap when: existing_start < new_end AND existing_end > new_start
            // starts_at NULL means "immediately" (treat as past)
            $hasOverlapping = ManagementReviewDelegation::query()
                ->where('user_id', $this->user()->id)
                ->whereNull('ended_early_at')
                ->where(function (\Illuminate\Contracts\Database\Query\Builder $q) use ($endsAt): void {
                    $q->whereNull('starts_at')
                        ->orWhere('starts_at', '<', $endsAt);
                })
                ->where('ends_at', '>', $startsAt)
                ->exists();

            if ($hasOverlapping) {
                $validator->errors()->add('delegate_user_id', __('You already have a delegation that overlaps with this date range.'));
            }

            $delegateUserId = $this->input('delegate_user_id');
            if ($delegateUserId) {
                $delegate = \App\Models\User::query()->find($delegateUserId);
                if ($delegate && $delegate->isActingAsDelegate()) {
                    $validator->errors()->add('delegate_user_id', __('This user is already acting as a delegate.'));
                }
            }
        });
    }
}
