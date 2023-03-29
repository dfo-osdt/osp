<?php

namespace App\Http\Controllers;

use App\Http\Resources\FunderResource;
use App\Models\Funder;
use Illuminate\Http\Resources\Json\JsonResource;

class FunderController extends Controller
{
    /**
     * Return a list of funders. There is not many at this point so
     * no need for pagination. We can add later if needed.
     */
    public function index(): JsonResource
    {
        return FunderResource::collection(Funder::all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Funder $funder): JsonResource
    {
        return FunderResource::make($funder);
    }
}
