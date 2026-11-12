<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;
use VincentAuger\OrcidSdk\Support\OrcidId;

class Orcid implements ValidationRule
{
    /**
     * The links to an ORCID record we support: orcid.org and the sandbox
     */
    private const string URL_PREFIX = '/^https:\/\/(sandbox\.)?orcid\.org\//';

    /**
     * The bare iD of a valid ORCID iD given bare or as a link to orcid.org or the sandbox, null for anything else.
     */
    public static function bareId(mixed $value): ?string
    {
        if (! is_string($value) || ! OrcidId::isValid($value)) {
            return null;
        }

        if (str_contains($value, '/') && ! preg_match(self::URL_PREFIX, $value)) {
            return null;
        }

        return OrcidId::normalize($value);
    }

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
        if (! OrcidId::isValid($orcid)) {
            $fail(__('validation.orcid.checksum', ['attribute' => $attribute]));
        }
    }
}
