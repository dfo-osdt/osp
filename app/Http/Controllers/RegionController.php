<?php

namespace App\Http\Controllers;

use App\Http\Resources\RegionResource;
use App\Models\Region;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): ResourceCollection
    {
        return RegionResource::collection(Region::all());
    }
}
