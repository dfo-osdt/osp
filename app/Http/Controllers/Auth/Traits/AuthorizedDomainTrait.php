<?php

namespace App\Http\Controllers\Auth\Traits;

use Illuminate\Validation\ValidationException;

trait AuthorizedDomainTrait
{
    /**
     * Check if the email domain is part of the allowed domains
     */
    public function isEmailDomainAllowed(string $email): bool
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

    /**
     * Validate the email domain and throw validation
     * exception if not allowed
     */
    public function validateEmailDomain(string $email): void
    {
        if (! $this->isEmailDomainAllowed($email)) {
            throw ValidationException::withMessages([
                'email' => __('Email domain not allowed'),
            ]);
        }
    }
}
