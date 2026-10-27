<?php

namespace App\Http\Controllers\Orcid;

use App\Jobs\SyncAuthorEmploymentWithOrcid;
use App\Jobs\SyncAuthorFromOrcid;
use App\Models\User;
use App\Orcid\OrcidClientFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Spatie\Activitylog\Facades\Activity as ActivityLog;
use VincentAuger\OrcidSdk\Enums\Scope;
use VincentAuger\OrcidSdk\Exceptions\OrcidException;

/**
 * Implements the 3-legged OAuth flow for ORCID. More information can be found at
 * https://info.orcid.org/documentation/api-tutorials/api-tutorial-get-and-authenticated-orcid-id/
 */
class FullFlowController
{
    public function __construct(private readonly OrcidClientFactory $orcid) {}

    public function callback(Request $request): RedirectResponse
    {
        Log::debug('Callback from ORCID');

        $frontendUrl = config('app.frontend_url');

        $validated = $request->validate([
            // authorization codes are URL-safe: letters, digits, "-" and "_"
            'code' => ['string', 'regex:/^[A-Za-z0-9_-]+$/'],
            'key' => ['required', 'string', 'alpha_num'],
            'error' => ['string'],
            'error_description' => ['required_with:error', 'string'],
        ]);

        if (! Cache::has($validated['key'])) {
            Log::error('Invalid key for ORCID callback');
            $url = $frontendUrl.'#/auth/orcid-callback?status=invalid_key';

            return redirect($url);
        }

        Log::info('Key found for ORCID callback');

        $user = User::query()->find(Cache::pull($validated['key']));

        if (! $user) {
            Log::error('Invalid user for ORCID callback');
            $url = $frontendUrl.'#/auth/orcid-callback?status=invalid_user';

            return redirect($url);
        }

        if ($validated['error'] ?? false) {
            Log::error('Error in ORCID callback');
            Log::debug($validated['error_description']);

            return redirect($frontendUrl.'#/auth/orcid-callback?status=error&error_type='.$validated['error'].'&error_description='.$validated['error_description']);
        }

        if (! isset($validated['code'])) {
            Log::error('No authorization code in ORCID callback');

            return redirect($frontendUrl.'#/auth/orcid-callback?status=failed');
        }

        Log::info('Requesting access token from ORCID');

        try {
            $token = $this->orcid->oauth()->exchangeCode($validated['code'], $this->redirectUri($validated['key']));
        } catch (OrcidException $exception) {
            Log::error('Failed to get access token from ORCID', ['error' => $exception->getMessage()]);

            return redirect($frontendUrl.'#/auth/orcid-callback?status=failed');
        }

        Log::info('Access token received from ORCID');

        ActivityLog::defaultCauser($user);

        $author = $user->author;

        $author->orcid = $this->orcid->environment->oauthUrl().'/'.$token->orcid;
        $author->orcid_access_token = $token->accessToken;
        $author->orcid_token_scope = Scope::encode($token->scopes);
        $author->orcid_refresh_token = $token->refreshToken;
        $author->orcid_verified = true;
        $author->orcid_expires_at = now()->addSeconds($token->expiresIn);
        $author->save();

        // pick up what changed on ORCID, then push what changed in OSP while the
        // author was not connected
        dispatch(new SyncAuthorFromOrcid($author));
        $pending = SyncAuthorEmploymentWithOrcid::dispatchPendingFor($author);
        Log::info('ORCID connected', ['author_id' => $author->id, 'pending_employments' => $pending]);

        return redirect($frontendUrl.'#/auth/orcid-callback?status=success');
    }

    /**
     * Returns the link to the ORCID authorization endpoint with the required parameters.
     */
    public function redirect(Request $request): RedirectResponse
    {
        if (! $this->orcid->isConfigured()) {
            throw ValidationException::withMessages(['orcid' => __('Server side ORCID configuration is missing - please contact the administrator.')]);
        }

        // cache the user id with a random string for 15 mintues
        $key = Str::random(16);
        Cache::add($key, Auth::id(), now()->addMinutes(15));

        $link = $this->orcid->oauth()->authorizationUrl(
            scopes: $this->orcid->scopes(),
            redirectUri: $this->redirectUri($key),
            lang: app()->getLocale(),
        );

        Log::info('Redirecting to ORCID for authorization');

        return redirect($link);
    }

    /**
     * The key identifying the user is added to the redirect URI; ORCID requires the
     * exact same URI when exchanging the code.
     */
    private function redirectUri(string $key): string
    {
        return $this->orcid->redirectUri.'?key='.$key;
    }
}
