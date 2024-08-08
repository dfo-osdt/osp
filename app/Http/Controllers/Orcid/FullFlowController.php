<?php

namespace App\Http\Controllers\Orcid;

use App\Enums\ORCID\ORCIDAuthScope;
use App\Models\User;
use App\Traits\LocaleTrait;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Spatie\Activitylog\Facades\CauserResolver;

/**
 * Implements the 3-legged OAuth flow for ORCID. More information can be found at
 * https://info.orcid.org/documentation/api-tutorials/api-tutorial-get-and-authenticated-orcid-id/
 */
class FullFlowController
{
    use LocaleTrait;

    public function callback(Request $request): RedirectResponse
    {
        Log::debug("Callback from ORCID");
        Log::debug($request->all());

        $frontendUrl = config('app.frontend_url');

        $validated = $request->validate([
            'code' => 'required|string|alpha_num',
            'key' => 'required|string|alpha_num',
        ]);

        if (! Cache::has($validated['key'])) {
            Log::error('Invalid key for ORCID callback');
            $url = $frontendUrl . '#/auth/orcid-callback?status=invalid-key';
            return redirect($url);
        }

        Log::info('Key found for ORCID callback');

        $user = User::find(Cache::pull($validated['key']));

        if (! $user) {
            Log::error('Invalid user for ORCID callback');
            $url = $frontendUrl . '#/auth/orcid-callback?status=invalid-user';
            return redirect($url);
        }

        Log::info('User found for ORCID callback');
        Log::debug($user);

        $baseUrl = $this->getBaseUrl();

        $payload = [
            'client_id' => config('osp.orcid.client_id'),
            'client_secret' => config('osp.orcid.client_secret'),
            'grant_type' => 'authorization_code',
            'code' => $validated['code'],
            'redirect_uri' => config('osp.orcid.redirect_uri').'?key='.$validated['key'],
        ];

        Log::info('Requesting access token from ORCID');
        $response = Http::asForm()->accept('application/json')->post("https://$baseUrl/oauth/token", $payload);

        if ($response->failed()) {
            Log::error('Failed to get access token from ORCID');
            Log::debug($response->body());
            return redirect($frontendUrl . '#/auth/orcid-callback?status=failed');
        }

        Log::info('Access token received from ORCID');
        $accessToken = $response->json('access_token');
        $expiresIn = $response->json('expires_in');
        $refreshToken = $response->json('refresh_token');
        $scope = $response->json('scope');
        $orcid = $response->json('orcid');

        // we shoudl be able to use the access token to get the user's ORCID iD
        //$orcid = $this->getOrcidId($accessToken);

        CauserResolver::setCauser($user);

        $author = $user->author;

        $author->orcid = 'https://' . $baseUrl . '/' . $orcid;
        $author->orcid_access_token = $accessToken;
        $author->orcid_token_scope = $scope;
        $author->orcid_refresh_token = $refreshToken;
        $author->orcid_verified = true;
        $author->orcid_expires_at = now()->addSeconds($expiresIn);
        $author->save();

        return redirect($frontendUrl . '#/auth/orcid-callback?status=success');
    }

    /**
     * Returns the link to the ORCID authorization endpoint with the required parameters.
     *
     * @return void
     */
    public function redirect(Request $request): RedirectResponse
    {
        // get current locale
        $locale = $this->getLocaleFromRequest($request);

        $clientID = config('osp.orcid.client_id');
        $redirectURI = config('osp.orcid.redirect_uri');

        if (! $clientID || ! $redirectURI) {
            throw ValidationException::withMessages(['orcid' => __('Server side ORCID configuration is missing - please contact the administrator.')]);
        }

        // cache the user id with a random string for 15 mintues
        $key = Str::random(16);
        Cache::add($key, Auth::id(), now()->addMinutes(15));

        // add key to redirect URI
        $redirectURI .= '?key=' . $key;

        // encode URI - if it's not encoded, ORCID will returns to base URL
        $encoded = urlencode($redirectURI);
        $scope = ORCIDAuthScope::completeAccess();

        $base_url = $this->getBaseUrl();

        // build the link
        $link = "https://$base_url/oauth/authorize?client_id=$clientID&response_type=code&scope=$scope&redirect_uri=$encoded&lang=$locale";

        Log::info('Redirecting to ORCID for authorization');
        Log::debug($link);
        // redirect to ORCID
        return redirect($link);
    }

    protected function getBaseUrl(): string
    {
        return config('osp.orcid.use_sandbox') ? 'sandbox.orcid.org' : 'orcid.org';
    }
}
