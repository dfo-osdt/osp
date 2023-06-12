<?php

namespace App\Http\Controllers\OhDear;

use App\Http\Controllers\Controller;
use App\Traits\LocaleTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CheckStatusPageController extends Controller
{
    use LocaleTrait;

    /**
     * Handle the incoming request for the OhDear status json page.
     */
    public function __invoke(Request $request)
    {

        if (! config('osp.ohdear.enabled')) {
            return response()->json([
                'message' => 'Status Monitoring is not enabled',
            ], 202);
        }

        $locale = $this->getLocaleFromRequest($request);
        $url = config('osp.ohdear.url').'/json?locale='.$locale;
        $rememberFor = 60 * 10;

        $statusResponse = Cache::remember($url, $rememberFor, function () use ($url) {
            return Http::get($url)->json();
        });

        return response()->json($statusResponse);

    }
}
