<?php

namespace App\Http\Controllers;

use App\Actions\Expertise\CreateExpertise;
use App\Actions\Expertise\SuggestExpertiseMatches;
use App\Http\Requests\StoreExpertiseRequest;
use App\Http\Resources\ExpertiseResource;
use App\Models\Expertise;
use App\Queries\ExpertiseListQuery;
use App\Traits\PaginationLimitTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpertiseController extends Controller
{
    use PaginationLimitTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResource
    {
        $limit = $this->getLimitFromRequest($request);
        $expertiseListQuery = new ExpertiseListQuery;

        return ExpertiseResource::collection($expertiseListQuery->paginate($limit));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpertiseRequest $request): ExpertiseResource
    {
        $expertise = CreateExpertise::handle(
            $request->validated(),
            $request->user(),
        );

        return new ExpertiseResource($expertise);
    }

    /**
     * Display the specified resource.
     */
    public function show(Expertise $expertise): ExpertiseResource
    {
        return new ExpertiseResource($expertise);
    }

    /**
     * Find similar expertise records.
     */
    public function similar(Request $request): JsonResponse
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
