<?php

namespace App\Http;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Str;
use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policies\Policy;
use Symfony\Component\HttpFoundation\Response;

class OspCspPolicy extends Policy
{
    public function configure()
    {
        $this
            ->addDirective(Directive::BASE, Keyword::SELF)
            ->addDirective(Directive::CONNECT, Keyword::SELF)
            ->addDirective(Directive::DEFAULT, Keyword::SELF)
            ->addDirective(Directive::FORM_ACTION, Keyword::SELF)
            ->addDirective(Directive::IMG, Keyword::SELF)
            ->addDirective(Directive::MEDIA, Keyword::SELF)
            ->addDirective(Directive::OBJECT, Keyword::NONE)
            ->addDirective(Directive::SCRIPT, Keyword::SELF)
            ->addDirective(Directive::STYLE, Keyword::SELF)
            ->addDirective(Directive::SCRIPT_ELEM, Keyword::SELF);

    }

    /**
     * When should the policy be applied?
     */
    public function shouldBeApplied(Request $request, Response $response): bool
    {

        /**
         * If Vite is running in hot module replacement mode, we don't want to apply CSP
         * because the inline script injected by Vite will be blocked by the CSP policy.
         */
        if (Vite::isRunningHot()) {
            return false;
        }

        /**
         * We don't want to apply CSP to the following paths.
         */
        $excludedPaths = ['pulse', 'health', 'horizon'];
        foreach ($excludedPaths as $path) {
            if (Str::startsWith($request->path(), $path)) {
                return false;
            }
        }


        // https://github.com/spatie/laravel-csp?tab=readme-ov-file#using-whoops
        if (config('app.debug') && ($response->isClientError() || $response->isServerError())) {
            return false;
        }

        return parent::shouldBeApplied($request, $response);
    }
}
