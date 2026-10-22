<?php

namespace App\Http\Controllers\Orcid;

use App\Orcid\OrcidClientFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use VincentAuger\OrcidSdk\Exceptions\OrcidException;

class RevokeTokenController
{
    public function __invoke(OrcidClientFactory $orcid): JsonResponse
    {
        $author = Auth::user()->author;
        Log::info('Revoking ORCID token for author '.$author->id);

        // Does author have an ORCID token?
        if (! $author->orcid_access_token) {
            Log::info('Author '.$author->id.' does not have an ORCID token');

            throw ValidationException::withMessages([
                'author' => 'Author does not have an ORCID token',
            ]);
        }

        try {
            $orcid->oauth()->revoke($author->orcid_access_token);
        } catch (OrcidException $exception) {
            Log::error('Failed to revoke ORCID token for author '.$author->id, ['error' => $exception->getMessage()]);

            throw ValidationException::withMessages([
                'author' => 'Failed to revoke token with ORCID, try again later',
            ]);
        }

        $author->clearOrcidToken();

        Log::info('ORCID token revoked for author '.$author->id);

        return response()->json([
            'message' => 'ORCID token revoked',
        ]);
    }
}
