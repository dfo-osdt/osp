<?php

namespace App\Queries;

use App\Models\ManuscriptRecord;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class MyManuscriptsRecordQuery extends QueryBuilder
{
    public function __construct(?\Illuminate\Http\Request $request, $baseQuery = null)
    {
        parent::__construct($baseQuery ?? ManuscriptRecord::query(), $request);

        $this->
         defaultSort('updated_at')->
         allowedFilters([
             AllowedFilter::exact('user_id'),
             AllowedFilter::exact('region_id'),
             AllowedFilter::exact('status'),
             AllowedFilter::exact('type'),
             AllowedFilter::partial('title'),
         ])->allowedSorts([
             'updated_at',
             'created_at',
             'title',
             'type',
             'status',
         ]);
    }
}
