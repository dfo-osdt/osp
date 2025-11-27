<?php

namespace App\Http\Controllers\Orcid;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthorResource;
use App\Traits\LocaleTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class ImplicitFlowController extends Controller
{
    use LocaleTrait;

    /**
     * Handles POST request from client that has used the ORCID Implicit Flow and received
     * an access token. The access token is then used to retrieve the ORCID iD of the user
     * directly from ORCID. With the implicit flow, the token is short-lived and is only
     * valid for 10 minutes.
     */
    public function __invoke(Request $request): JsonResource
    {
        // validate the request
        $request->validate([
            'access_token' => ['required'],
        ]);

        // get the ORCID iD from ORCID using the access token
        $orcidId = $this->getOrcidId($request->access_token);

        // update the user's author record in the database
        $user = Auth::user();

        $user->author->orcid = $orcidId;
        $user->author->orcid_verified = true;
        $user->author->save();

        // send updated author record (resource) back to client
        return AuthorResource::make($user->author);
    }

    /**
     * Get the ORCID iD of the user from ORCID using the access token.
     *
     * The ORCID iD is returned as the 'sub' key in the JSON response.
     * Full response example:
     * [
     *  "id" => "https://orcid.org/0000-0002-0595-6576",
     *  "sub" => "0000-0002-0595-6576",
     *  "name" => null,
     *  "family_name" => "Auger",
     *  "given_name" => "Vincent",
     * ]
     *
     * Documentation: https://info.orcid.org/documentation/api-tutorials/api-tutorial-get-and-authenticated-orcid-id/
     *
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function getOrcidId(string $accessToken): string
    {

        $base_url = $this->getBaseUrl();

        $response = Http::withToken($accessToken)->get("https://$base_url/oauth/userinfo");

        if ($response->failed()) {
            throw ValidationException::withMessages(['orcid' => __('Unable to retrieve ORCID iD from ORCID.')]);
        }

        return $response->json()['id'];
    }

    /**
     * Returns the link to the ORCID authorization endpoint with the required parameters.
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

        // encode URI - if it's not encoded, ORCID will returns to base URL
        $encoded = urlencode((string) $redirectURI);

        $base_url = $this->getBaseUrl();

        // build the link
        $link = "https://$base_url/oauth/authorize?client_id=$clientID&response_type=token&scope=/authenticate&redirect_uri=$encoded&lang=$locale";

        // redirect to ORCID
        return redirect($link);
    }

    public function getBaseUrl(): string
    {
        return config('osp.orcid.use_sandbox') ? 'sandbox.orcid.org' : 'orcid.org';
    }
}
