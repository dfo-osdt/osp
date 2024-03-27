<?php

namespace App\Http\Controllers\Orcid;

use App\Enums\ORCID\ORCIDAuthScope;
use App\Http\Resources\AuthorResource;
use App\Traits\LocaleTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

/**
 * Implements the 3-legged OAuth flow for ORCID. More information can be found at
 * https://info.orcid.org/documentation/api-tutorials/api-tutorial-get-and-authenticated-orcid-id/
 */
class FullFlowController
{
    use LocaleTrait;

    public function __invoke(Request $request): JsonResource
    {
        $validated = $request->validate([
            'code' => 'required|string|alpha_num',
        ]);

        $baseUrl = $this->getBaseUrl();

        $payload = [
            'client_id' => config('osp.orcid.client_id'),
            'client_secret' => config('osp.orcid.client_secret'),
            'grant_type' => 'authorization_code',
            'code' => $validated['code'],
            'redirect_uri' => config('osp.orcid.redirect_uri'),
        ];

        $response = Http::asForm()->accept('application/json')->post("https://$baseUrl/oauth/token", $payload);

        if ($response->failed()) {
            throw ValidationException::withMessages(['code' => 'Invalid code']);
        }

        $accessToken = $response->json('access_token');
        $expiresIn = $response->json('expires_in');
        $refreshToken = $response->json('refresh_token');
        $scope = $response->json('scope');
        $orcid = $response->json('orcid');

        // we shoudl be able to use the access token to get the user's ORCID iD
        //$orcid = $this->getOrcidId($accessToken);

        // get user's author record
        $user = auth()->user();
        $author = $user->author;

        $author->orcid = 'https://' . $baseUrl . '/' . $orcid;
        $author->orcid_access_token = $accessToken;
        $author->orcid_token_scope = $scope;
        $author->orcid_refresh_token = $refreshToken;
        $author->orcid_verified = true;
        $author->orcid_expires_at = now()->addSeconds($expiresIn);
        $author->save();

        return AuthorResource::make($author);
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

        if (!$clientID || !$redirectURI) {
            throw ValidationException::withMessages(['orcid' => __('Server side ORCID configuration is missing - please contact the administrator.')]);
        }

        // encode URI - if it's not encoded, ORCID will returns to base URL
        $encoded = urlencode($redirectURI);
        $scope = ORCIDAuthScope::completeAccess();

        $base_url = $this->getBaseUrl();

        // build the link
        $link = "https://$base_url/oauth/authorize?client_id=$clientID&response_type=code&scope=$scope&redirect_uri=$encoded&lang=$locale";

        // redirect to ORCID
        return redirect($link);
    }


    /**
     * Redirects to the frontend with the ORCID code. This is required as the OAuth server
     * will not accept an URL with a hash in the 3-legged flow.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function redirectToFrontend(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|alpha_num',
        ]);

        $frontendUrl = config('app.frontend_url');
        $url = $frontendUrl . "#/auth/orcid-callback?code=" . $validated['code'];

        return redirect($url);
    }

    protected function getBaseUrl(): string
    {
        return config('osp.orcid.use_sandbox') ? 'sandbox.orcid.org' : 'orcid.org';
    }
}
