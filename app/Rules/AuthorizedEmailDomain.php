<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AuthorizedEmailDomain implements ValidationRule
{
    /**
     * Determine if the email is a valid domain.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $this->isEmailDomainAllowed($value)) {
            $fail(__('Email domain not allowed'));
        }
    }

    /**
     * Check if the email domain is part of the allowed domains
     */
    private function isEmailDomainAllowed(string $email): bool
    {
        $allowedDomains = config('osp.allowed_registration_email_domains');

        if ($allowedDomains) {
            $emailDomain = explode('@', $email)[1];
            if (! in_array($emailDomain, $allowedDomains)) {
                return false;
            }
        }

        return true;
    }
}
