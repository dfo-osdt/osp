<?php

namespace App\Rules;

use App\Http\Controllers\Auth\Traits\AuthorizedDomainTrait;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AuthorizeEmailDomain implements ValidationRule
{
    use AuthorizedDomainTrait;

    /**
     * Determine if the email is a valid domain.
     *
     * @return Closure
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $this->isEmailDomainAllowed($value)) {
            $fail('The email domain is not allowed.');
        }
    }
}
