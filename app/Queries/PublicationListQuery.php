<?php

namespace App\Queries;

use App\Models\Publication;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PublicationListQuery extends QueryBuilder
{
    public function __construct(?\Illuminate\Http\Request $request, $baseQuery = null)
    {
        parent::__construct($baseQuery ?? Publication::query(), $request);

        $this->
         defaultSort('title')->
         allowedSorts(['title', 'created_at', 'updated_at', 'published_on'])->
         allowedFilters([
             AllowedFilter::exact('id'),
             AllowedFilter::exact('status'),
             AllowedFilter::exact('user_id'),
             AllowedFilter::exact('journal_id'),
             AllowedFilter::exact('region_id'),
             AllowedFilter::exact('publicationAuthors.author_id'),
             AllowedFilter::partial('title'),
             AllowedFilter::scope('open_access'),
             AllowedFilter::scope('not_under_embargo'),
             AllowedFilter::scope('under_embargo'),
             AllowedFilter::scope('secondary_publication'),
         ]);
    }
}
