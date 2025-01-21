<?php

namespace App\DTOs;

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
}
