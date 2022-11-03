<?php

namespace App\Queries;

use App\Filters\FuzzyFilter;
use App\Models\Publication;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PublicationListQuery extends QueryBuilder
{
    public function __construct($request, $baseQuery = null)
    {
        parent::__construct($baseQuery ?? Publication::query(), $request);

        $this->
         defaultSort('title')->
         allowedSorts('title')->
         allowedFilters([
             AllowedFilter::exact('id'),
             AllowedFilter::exact('user_id'),
             AllowedFilter::partial('title'),
             AllowedFilter::custom('search', new FuzzyFilter('title')),
             AllowedFilter::scope('open_access'),
             AllowedFilter::scope('not_under_embargo'),
             AllowedFilter::scope('under_embargo'),
         ]);
    }
}
