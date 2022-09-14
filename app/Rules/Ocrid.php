<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class Ocrid implements InvokableRule
{
    /**
     * Run the validation rule for an ORCID ID.
     * https://support.orcid.org/hc/en-us/articles/360006897674-Structure-of-the-ORCID-Identifier
     *
     * @param  string  $attribute
     * @param  mixed  $orcid
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        // check value against regex
        if (! preg_match('/^0000-000[1-9]-[0-9]{4}-[0-9]{3}[0-9X]$/', $value)) {
            $fail(__('validation.orcid.format', ['attribute' => $attribute]));
        }

        // check value against checksum
        if (! $this->isChecksumValid($value)) {
            $fail(__('validation.orcid.checksum', ['attribute' => $attribute]));
        }
    }

    /**
     * Generates check digit as per ISO 7064 11,2.
     */
    private function isChecksumValid(string $orcid): bool
    {
        if (strlen($orcid) != 19) {
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
        $checkDigit = $result == 10 ? 'X' : strval($result);

        return $checkDigit == $givenCheckSum;
    }
}
