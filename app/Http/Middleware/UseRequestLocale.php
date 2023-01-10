<?php

namespace App\Http\Middleware;

use App\Traits\LocaleTrait;
use Closure;
use Illuminate\Http\Request;

class UseRequestLocale
{
    use LocaleTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $this->setLocaleFromRequest($request);

        return $next($request);
    }
}
