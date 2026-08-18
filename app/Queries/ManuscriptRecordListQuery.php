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
class ManuscriptRecordListQuery extends QueryBuilder
{
    public function __construct(Builder|Relation|null $subject = null, ?Request $request = null)
    {
        parent::__construct($subject ?? ManuscriptRecord::query(), $request);

        $this->
         defaultSort('-created_at')->
         allowedSorts('title', 'created_at', 'updated_at', 'sent_for_review_at', 'accepted_on', 'submitted_to_journal_on')->
         allowedFilters(
             AllowedFilter::exact('id'),
             AllowedFilter::exact('status'),
             AllowedFilter::exact('type'),
             AllowedFilter::exact('user_id'),
             AllowedFilter::exact('region_id'),
             AllowedFilter::exact('functional_area_id'),
             AllowedFilter::exact('manuscriptAuthors.author_id'),
             AllowedFilter::partial('title'),
             AllowedFilter::partial('abstract'),
             AllowedFilter::custom('search', new MultiColumnFilter('title', 'ulid')),
             AllowedFilter::exact('potential_public_interest'),
             AllowedFilter::scope('reviewedBetween'),
             AllowedFilter::scope('overdueReview'),
         );
    }
}
