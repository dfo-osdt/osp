<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Doi implements ValidationRule
{
    /**
     * Check if the given value is a valid DOI.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // check the value against the DOI regex
        if (! preg_match('/^10\.\d{4,9}\/[-._;()\/:A-Z0-9]+$/i', $value)) {
            $fail('The :attribute is not a valid DOI.');
        }
    }
}
