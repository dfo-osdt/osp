<?php

namespace App\Http\Controllers;

use App\Actions\Expertise\CreateExpertise;
use App\Http\Requests\StoreExpertiseRequest;
use App\Http\Resources\ExpertiseResource;
use App\Models\Expertise;
use App\Queries\ExpertiseListQuery;
use App\Traits\PaginationLimitTrait;
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
}
