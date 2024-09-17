<?php

namespace App\Http\Controllers\Orcid;

use App\Models\Author;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RevokeTokenController
{
    public function __invoke()
    {
        $author = Auth::user()->author;
        Log::info('Revoking ORCID token for author ' . $author->id);

        // Does author have an ORCID token?
        if (! $author->orcid_access_token) {
            Log::info('Author ' . $author->id . ' does not have an ORCID token');

            throw ValidationException::withMessages([
                'author' => 'Author does not have an ORCID token',
            ]);
        }

        $payload = [
            'client_id' => config('osp.orcid.client_id'),
            'client_secret' => config('osp.orcid.client_secret'),
            'token' => $author->orcid_access_token,
        ];

        // Revoke the token
        $baseUrl = $this->getBaseUrl();
        $response = Http::asForm()->accept('application/json')->post("https://$baseUrl/oauth/revoke", $payload);

        if ($response->status() !== 200) {
            Log::error('Failed to revoke ORCID token for author ' . $author->id);
            Log::error($response->json());

            throw ValidationException::withMessages([
                'author' => 'Failed to revoke token with ORCID, try again later',
            ]);
        }

        $author->clearOrcidToken();

        Log::info('ORCID token revoked for author ' . $author->id);

        return response()->json([
            'message' => 'ORCID token revoked',
        ]);
    }

    private function getBaseUrl(): string
    {
        return config('osp.orcid.use_sandbox') ? 'sandbox.orcid.org' : 'orcid.org';
    }
}
