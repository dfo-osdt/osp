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
            'state' => ['required', 'string', 'alpha_num'],
            'error' => ['string'],
            'error_description' => ['required_with:error', 'string'],
        ]);

        if (! Cache::has($validated['state'])) {
            Log::error('Invalid state for ORCID callback');
            $url = $frontendUrl.'#/auth/orcid-callback?status=invalid_key';

            return redirect($url);
        }

        Log::info('State found for ORCID callback');

        // the state must belong to this browser's session, otherwise the flow was started elsewhere (CSRF)
        $expected = $request->session()->pull('orcid_state');

        if (! is_string($expected) || ! hash_equals($expected, $validated['state'])) {
            Log::error('ORCID callback state does not match the session');

            // burn the state so a planted link cannot be replayed in other browsers
            $this->logRejectedCallback($request, is_string($expected), User::query()->find(Cache::pull($validated['state'])));

            return redirect($frontendUrl.'#/auth/orcid-callback?status=invalid_state');
        }

        $user = User::query()->find(Cache::pull($validated['state']));

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
            $token = $this->orcid->oauth()->exchangeCode($validated['code'], (string) $this->orcid->redirectUri);
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

        // the state binds the flow to this browser's session and maps to the user id for 15 minutes
        $state = Str::random(16);
        $request->session()->put('orcid_state', $state);
        Cache::add($state, Auth::id(), now()->addMinutes(15));

        $link = $this->orcid->oauth()->authorizationUrl(
            scopes: $this->orcid->scopes(),
            redirectUri: (string) $this->orcid->redirectUri,
            state: $state,
            lang: app()->getLocale(),
        );

        Log::info('Redirecting to ORCID for authorization');

        return redirect($link);
    }

    /**
     * Records a callback rejected because its state is not in this browser's session. It is
     * attributed to the user who started the flow; when another user's browser completed it,
     * someone tried to link that user's ORCID iD to the initiator's account (CSRF).
     */
    private function logRejectedCallback(Request $request, bool $sessionHadState, ?User $initiator): void
    {
        $completedBy = Auth::user();
        $byAnotherUser = $completedBy !== null && ! $completedBy->is($initiator);

        $logger = activity()
            ->event($byAnotherUser ? 'suspicious' : 'rejected')
            ->withProperties([
                'source' => 'orcid',
                'reason' => match (true) {
                    $byAnotherUser => 'completed_by_another_user',
                    ! $sessionHadState => 'no_state_in_session',
                    default => 'state_mismatch',
                },
                'completed_by_user_id' => $completedBy?->id,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

        // without an explicit causer the logged-in user (here the possible victim) is used
        if ($initiator instanceof \App\Models\User) {
            $logger->causedBy($initiator)->performedOn($initiator->author);
        } else {
            $logger->causedByAnonymous();
        }

        $logger->log($byAnotherUser
            ? 'ORCID connection started by this user was completed in another user\'s browser'
            : 'ORCID connection rejected: it was not completed in the browser that started it');
    }
}
