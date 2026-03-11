<?php

namespace App\Http\Controllers\Expertise;

use App\Actions\Expertise\SuggestExpertiseMatches;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExpertiseResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SuggestSimilarExpertiseController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'name_en' => ['nullable', 'string', 'max:255'],
            'name_fr' => ['nullable', 'string', 'max:255'],
        ]);

        $matches = SuggestExpertiseMatches::handle(
            nameEn: $request->input('name_en', ''),
            nameFr: $request->input('name_fr', ''),
        );

        return response()->json([
            'data' => $matches->map(fn (array $match): array => [
                'expertise' => new ExpertiseResource($match['expertise']),
                'score' => $match['score'],
                'matched_on' => $match['matched_on'],
            ]),
        ]);
    }
}
