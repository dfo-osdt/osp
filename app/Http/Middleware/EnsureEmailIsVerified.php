<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  string|null  $redirectToRoute
     * @return JsonResponse|RedirectResponse|null
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        if (! $request->user() ||
            (! $request->user()->hasVerifiedEmail())) {
            return response()->json(['message' => 'Your email address is not verified.'], 409);
        }

        return $next($request);
    }
}
