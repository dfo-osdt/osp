<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

trait LocaleTrait
{
    /**
     * Set the Laravel locale from the given local value
     * string $locale where the locale is a valid Laravel locale
     *
     * @return void
     *
     * @throws ValidationException if the locale is not valid
     */
    public function setLocaleFromRequest(Request $locale)
    {
        $validated = $locale->validate([
            'locale' => ['string', 'max:2', 'in:en,fr'],
        ]);

        if (isset($validated['locale'])) {
            app()->setLocale($validated['locale']);
        }
    }

    /**
     * Get Locale from request or defaults to 'en' if missing
     */
    public function getLocaleFromRequest(Request $request): string
    {
        $locale = $request->validate([
            'locale' => ['string', 'max:2', 'in:en,fr'],
        ]);

        return $locale['locale'] ?? 'en';
    }
}
