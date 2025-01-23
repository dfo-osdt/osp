<?php

namespace App\DTOs;

use Microsoft\Graph\Generated\Models\User;
use Spatie\LaravelData\Data;

class RegisterUserData extends Data
{
    public function __construct(
        public string $first_name,
        public string $last_name,
        public string $email,
        public ?string $password = null,
        public ?string $locale = 'en'
    ) {
        $this->email = strtolower($this->email); // Ensure email is lowercase
    }

    public static function fromMsGraphUser(User $user): self
    {
        return new self(
            first_name: $user->getGivenName(),
            last_name: $user->getSurname(),
            email: $user->getMail(),
            locale: $user->getPreferredLanguage() === 'fr-CA' ? 'fr' : 'en'
        );
    }
}
