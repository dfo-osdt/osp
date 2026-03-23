<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\Http\Middleware\AuthenticateSession as SanctumAuthenticateSession;
use Symfony\Component\HttpFoundation\Response;

/**
 * Custom AuthenticateSession middleware that extends Sanctum's implementation
 * to handle users with null passwords (e.g., Azure OAuth users).
 *
 * This middleware prevents TypeErrors when the AuthenticateSession middleware
 * encounters users who authenticate via OAuth and do not have a password hash.
 */
class AuthenticateSession extends SanctumAuthenticateSession
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return $next($request);
        }

        // Skip password hash validation for users without passwords (OAuth users)
        if ($request->user()->password === null) {
            return $next($request);
        }

        // For users with passwords, use the parent implementation
        return parent::handle($request, $next);
    }
}
