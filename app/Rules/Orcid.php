<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class Orcid implements ValidationRule
{
    /**
     * The links to an ORCID record we support: orcid.org and the sandbox
     */
    public const string URL_PREFIX = '/^https:\/\/(sandbox\.)?orcid\.org\//';

    /**
     * Run the validation rule for an ORCID ID, given bare or as a link to the record.
     * https://support.orcid.org/hc/en-us/articles/360006897674-Structure-of-the-ORCID-Identifier
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (str_contains((string) $value, '/') && ! preg_match(self::URL_PREFIX, (string) $value)) {
            $fail(__('validation.orcid.prefix', ['attribute' => $attribute]));
        }

        // check remainder against only the ORCID part
        $orcid = Str::substr($value, -19);

        // check value against regex
        if (! preg_match('/^\d{4}-\d{4}-\d{4}-\d{3}[0-9X]$/', $orcid)) {
            $fail(__('validation.orcid.format', ['attribute' => $attribute]));
        }

        // check value against checksum
        if (! $this->isChecksumValid($orcid)) {
            $fail(__('validation.orcid.checksum', ['attribute' => $attribute]));
        }
    }

    /**
     * Generates check digit as per ISO 7064 11,2.
     */
    private function isChecksumValid(string $orcid): bool
    {
        if (strlen($orcid) !== 19) {
            return false;
        }
        $orcid = str_replace('-', '', $orcid);
        $sum = 0;
        $givenCheckSum = substr($orcid, -1);

        for ($i = 0; $i < 15; $i++) {
            $digit = intval($orcid[$i]);
            $sum = ($sum + $digit) * 2;
        }
        $remainder = $sum % 11;
        $result = (12 - $remainder) % 11;
        $checkDigit = $result === 10 ? 'X' : strval($result);

        return $checkDigit === $givenCheckSum;
    }
}
