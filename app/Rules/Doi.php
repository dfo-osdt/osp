<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class Doi implements InvokableRule
{
    /**
     * Check if the given value is a valid DOI.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        // check the value against the DOI regex
        if (! preg_match('/^10\.\d{4,9}\/[-._;()\/:A-Z0-9]+$/i', $value)) {
            $fail('The :attribute is not a valid DOI.');
        }
    }
}
