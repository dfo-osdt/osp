<?php

namespace App\Http;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
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
     * We override this method to prevent CSP from being applied in debug mode
     * so that Laravel whoops error handler can display the error page.
     *
     * https://github.com/spatie/laravel-csp?tab=readme-ov-file#using-whoops
     */
    public function shouldBeApplied(Request $request, Response $response): bool
    {

        /**
         * If Vite is running in hot module replacement mode, we don't want to apply CSP
         * because the inline script injected by Vite will be blocked by the CSP policy.
         */
        if(Vite::isRunningHot()) {
            return false;
        }

        if (config('app.debug') && ($response->isClientError() || $response->isServerError())) {
            return false;
        }

        return parent::shouldBeApplied($request, $response);
    }
}
