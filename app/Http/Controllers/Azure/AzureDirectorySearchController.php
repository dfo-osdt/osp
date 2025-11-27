<?php

namespace App\Http\Controllers\Azure;

use App\Http\Controllers\Controller;
use App\Http\Resources\AzureDirectoryUserResource;
use App\Services\MicrosoftGraphService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class AzureDirectorySearchController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'search' => ['required', 'string', 'min:3'],
        ]);

        $needle = strtolower((string) $validated['search']);

        /**
         * Get an instance of the MicrosoftGraphService class using the Laravel service container.
         *
         * @var MicrosoftGraphService $microsoftGraphService
         */
        $microsoftGraphService = App::make(MicrosoftGraphService::class);

        try {
            $users = $microsoftGraphService->searchForUserByEmail($needle);
        } catch (\Exception) {
            return response()->json([
                'message' => __('An error occurred while searching for users in the directory.'),
            ], 500);
        }

        return AzureDirectoryUserResource::collection($users);

    }
}
