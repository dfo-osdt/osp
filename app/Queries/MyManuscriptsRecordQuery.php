<?php

namespace App\Queries;

use App\Filters\MultiColumnFilter;
use App\Models\ManuscriptRecord;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

/** @extends QueryBuilder<ManuscriptRecord> */
class MyManuscriptsRecordQuery extends QueryBuilder
{
    public function __construct(Builder|Relation|null $subject = null, ?Request $request = null)
    {
        parent::__construct($subject ?? ManuscriptRecord::query(), $request);

        $this->
         defaultSort('updated_at')->
         allowedFilters(
             AllowedFilter::exact('user_id'),
             AllowedFilter::exact('region_id'),
             AllowedFilter::exact('status'),
             AllowedFilter::exact('type'),
             AllowedFilter::partial('title'),
             AllowedFilter::custom('search', new MultiColumnFilter('title', 'ulid')),
             AllowedFilter::exact('functional_area_id'),
             AllowedFilter::scope('reviewedBetween'),
         )->allowedSorts(
             'updated_at',
             'created_at',
             'title',
             'type',
             'status',
         );
    }
}
