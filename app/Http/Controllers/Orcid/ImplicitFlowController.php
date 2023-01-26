<?php

namespace App\Http\Controllers\Orcid;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthorResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class ImplicitFlowController extends Controller
{
    /**
     * Handles POST request from client that has used the ORCID Implicit Flow and received
     * an access token. The access token is then used to retrieve the ORCID iD of the user
     * directly from ORCID. With the implicit flow, the token is short-lived and is only
     * valid for 10 minutes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // validate the request
        $request->validate([
            'access_token' => 'required',
        ]);

        // get the ORCID iD from ORCID using the access token
        $orcidId = $this->getOrcidId($request->access_token);

        // update the user's author record in the database
        $user = auth()->user();

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
     * @param  string  $accessToken
     * @return string
     */
    protected function getOrcidId(string $accessToken)
    {
        $response = Http::withToken($accessToken)->get('https://orcid.org/oauth/userinfo');

        if ($response->failed()) {
            throw ValidationException::withMessages(['orcid' => __('Unable to retrieve ORCID iD from ORCID.')]);
        } else {
            return $response->json()['sub'];
        }
    }

    /**
     * Returns the link to the ORCID authorization endpoint with the required parameters.
     *
     * @return void
     */
    public function redirect()
    {
        $clientID = config('osp.orcid.client_id');
        $redirectURI = config('osp.orcid.redirect_uri');

        if (! $clientID || ! $redirectURI) {
            throw ValidationException::withMessages(['orcid' => __('Server side ORCID configuration is missing - please contact the administrator.')]);
        }

        // encode URI - if it's not encoded, ORCID will returns to base URL
        $encoded = urlencode($redirectURI);

        // build the link
        $link = "https://orcid.org/oauth/authorize?client_id=$clientID&response_type=token&scope=/authenticate&redirect_uri=$encoded";

        // redirect to ORCID
        return redirect($link);
    }
}
