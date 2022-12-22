<?php

namespace App\Http\Controllers;

use App\Http\Resources\FunderResource;
use App\Models\Funder;
use Illuminate\Http\Request;

class FunderController extends Controller
{
    /**
     * Return a list of funders. There is not many at this point so
     * no need for pagination. We can add later if needed.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return FunderResource::collection(Funder::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Funder  $funder
     * @return \Illuminate\Http\Response
     */
    public function show(Funder $funder)
    {
        return FunderResource::make($funder);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Funder  $funder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Funder $funder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Funder  $funder
     * @return \Illuminate\Http\Response
     */
    public function destroy(Funder $funder)
    {
        //
    }
}
