<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Queries\OrganizationListQuery;
use App\Traits\PaginationLimitTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrganizationController extends Controller
{
    use PaginationLimitTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): ResourceCollection
    {
        $limit = $this->getLimitFromRequest($request);
        $organizationListQuery = new OrganizationListQuery($request);

        return OrganizationResource::collection($organizationListQuery->paginate($limit));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResource
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:100|unique:organizations,name_en',
            'name_fr' => 'required|string|max:100|unique:organizations,name_fr',
            'abbr_en' => 'nullable|string|max:10',
            'abbr_fr' => 'nullable|string|max:10',
        ]);

        $organization = Organization::create($validated);

        return new OrganizationResource($organization);
    }

    /**
     * Display the specified resource.
     */
    public function show(Organization $organization): JsonResource
    {
        return new OrganizationResource($organization);
    }
}
