<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UseRequestLocale
{
    private const array SUPPORTED_LOCALES = ['en', 'fr'];

    /**
     * Set the application locale from the Accept-Language header,
     * falling back to a locale request parameter if present.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->resolveLocale($request);

        app()->setLocale($locale);

        return $next($request);
    }

    /**
     * Resolve the locale from the request. Priority:
     * 1. Accept-Language header
     * 2. locale request parameter (query or body)
     * 3. Default to 'en'
     */
    private function resolveLocale(Request $request): string
    {
        $locale = $request->header('Accept-Language');

        if ($locale && in_array($locale, self::SUPPORTED_LOCALES, true)) {
            return $locale;
        }

        $locale = $request->input('locale');

        if ($locale && in_array($locale, self::SUPPORTED_LOCALES, true)) {
            return $locale;
        }

        return config('app.locale', 'en');
    }
}
