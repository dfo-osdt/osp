<?php

namespace App\Queries;

use App\Models\ManagementReviewStep;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

/** @extends QueryBuilder<ManagementReviewStep> */
class ManagementReviewStepListQuery extends QueryBuilder
{
    public function __construct(?\Illuminate\Http\Request $request, $baseQuery = null)
    {
        parent::__construct($baseQuery ?? ManagementReviewStep::query(), $request);

        $this
            ->defaultSort('id')
            ->allowedSorts('updated_at', 'created_at')
            ->allowedIncludes(['manuscriptRecord', 'user'])
            ->allowedFilters([
                AllowedFilter::exact('status'),
                AllowedFilter::exact('decision'),
                AllowedFilter::exact('user_id'),
                AllowedFilter::partial('manuscriptRecord.title'),
            ]);
    }
}
