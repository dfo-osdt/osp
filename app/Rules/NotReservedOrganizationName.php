<?php

namespace App\Rules;

use App\Models\Organization;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NotReservedOrganizationName implements ValidationRule
{
    /**
     * Refuse the name reserved for the independent researcher organization.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (Organization::isIndependentName((string) $value)) {
            $fail(__('validation.reserved_organization_name', ['attribute' => $attribute]));
        }
    }
}
