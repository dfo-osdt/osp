<?php

namespace App\Queries;

use App\Models\ManagementReviewStep;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ManagementReviewStepListQuery extends QueryBuilder
{
    public function __construct($subject, $request)
    {
        parent::__construct($subject ?? ManagementReviewStep::query(), $request);

        $this
        ->defaultSort('id')
        ->allowedSorts('updated_at', 'created_at')
        ->allowedIncludes(['manuscriptRecord', 'user'])
        ->allowedFilters([
            AllowedFilter::exact('status'),
            AllowedFilter::exact('decision'),
            AllowedFilter::exact('user_id'),
        ]);
    }
}
