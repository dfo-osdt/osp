<?php

namespace App\Queries;

use App\Models\ManagementReviewStep;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

/** @extends QueryBuilder<ManagementReviewStep> */
class ManagementReviewStepListQuery extends QueryBuilder
{
    public function __construct(Builder|Relation|null $subject = null, ?Request $request = null)
    {
        parent::__construct($subject ?? ManagementReviewStep::query(), $request);

        $this
            ->defaultSort('id')
            ->allowedSorts('updated_at', 'created_at')
            ->allowedIncludes('manuscriptRecord', 'user')
            ->allowedFilters(
                AllowedFilter::exact('status'),
                AllowedFilter::exact('decision'),
                AllowedFilter::exact('user_id'),
                AllowedFilter::partial('manuscriptRecord.title'),
            );
    }
}
