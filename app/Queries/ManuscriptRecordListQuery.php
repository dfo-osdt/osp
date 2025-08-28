<?php

namespace App\Queries;

use App\Models\ManuscriptRecord;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ManuscriptRecordListQuery extends QueryBuilder
{
    public function __construct($request, $baseQuery = null)
    {
        parent::__construct($baseQuery ?? ManuscriptRecord::query(), $request);

        $this->
         defaultSort('-created_at')->
         allowedSorts(['title', 'created_at', 'updated_at', 'sent_for_review_at', 'accepted_on', 'submitted_to_journal_on'])->
         allowedFilters([
             AllowedFilter::exact('id'),
             AllowedFilter::exact('status'),
             AllowedFilter::exact('type'),
             AllowedFilter::exact('user_id'),
             AllowedFilter::exact('region_id'),
             AllowedFilter::exact('functional_area_id'),
             AllowedFilter::exact('manuscriptAuthors.author_id'),
             AllowedFilter::partial('title'),
             AllowedFilter::partial('abstract'),
             AllowedFilter::exact('potential_public_interest'),
         ]);
    }
}
