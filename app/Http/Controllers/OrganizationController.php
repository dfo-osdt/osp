<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Queries\OrganizationListQuery;
use App\Traits\PaginationLimitTrait;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    use PaginationLimitTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $this->getLimitFromRequest($request);
        $organizationListQuery = new OrganizationListQuery($request);

        return OrganizationResource::collection($organizationListQuery->paginate($limit));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
     *
     * @param  \App\Models\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function show(Organization $organization)
    {
        return new OrganizationResource($organization);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Organization $organization)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organization $organization)
    {
        //
    }
}
