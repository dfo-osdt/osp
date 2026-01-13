<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidListItems implements ValidationRule
{
    /**
     * @param  string[]  $allowedItems
     */
    public function __construct(
        private readonly array $allowedItems
    ) {}

    /**
     * Looks at the given value (a comma-separated list of items) and checks if
     * all items are in the list of allowed items.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $items = collect(explode(',', (string) $value))->map(fn ($item): string => trim($item))->unique();
        $items->each(function ($item) use ($fail, $attribute): void {
            if (! in_array($item, $this->allowedItems)) {
                $fail("$item is not a valid item for $attribute.");
            }
        });
    }
}
