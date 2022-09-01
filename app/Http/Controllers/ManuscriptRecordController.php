<?php

namespace App\Http\Controllers;

use App\Enums\ManuscriptRecordStatus;
use App\Enums\ManuscriptRecordType;
use App\Models\ManuscriptRecord;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class ManuscriptRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // validate that we have a type, title, and region_id
        $validated = $request->validate([
            'title' => 'required|max:255',
            'region_id' => 'required|exists:regions,id',
            'type' => ['required', new Enum(ManuscriptRecordType::class)],
        ]);

        $manuscript = new ManuscriptRecord($validated);
        $manuscript->status = ManuscriptRecordStatus::DRAFT;
        $manuscript->user_id = auth()->id();
        $manuscript->save();

        return $manuscript;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ManuscriptRecord  $manuscriptRecord
     * @return \Illuminate\Http\Response
     */
    public function show(ManuscriptRecord $manuscriptRecord)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ManuscriptRecord  $manuscriptRecord
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ManuscriptRecord $manuscriptRecord)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ManuscriptRecord  $manuscriptRecord
     * @return \Illuminate\Http\Response
     */
    public function destroy(ManuscriptRecord $manuscriptRecord)
    {
        //
    }
}
