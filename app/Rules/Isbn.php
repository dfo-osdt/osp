<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Isbn implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return;
        }

        // ISBN-13 must be exactly 13 digits
        if (! preg_match('/^\d{13}$/', (string) $value)) {
            $fail('validation.isbn')->translate();

            return;
        }

        // Validate ISBN-13 check digit
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += (int) $value[$i] * ($i % 2 === 0 ? 1 : 3);
        }
        $checkDigit = (10 - ($sum % 10)) % 10;

        if ((int) $value[12] !== $checkDigit) {
            $fail('validation.isbn')->translate();
        }
    }
}
