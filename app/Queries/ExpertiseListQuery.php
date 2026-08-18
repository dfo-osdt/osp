<?php

namespace App\Queries;

use App\Filters\MultiColumnFilter;
use App\Models\Expertise;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

/** @extends QueryBuilder<Expertise> */
class ExpertiseListQuery extends QueryBuilder
{
    public function __construct(Builder|Relation|null $subject = null, ?Request $request = null)
    {
        parent::__construct($subject ?? Expertise::query(), $request);

        $this
            ->defaultSort('name_en')
            ->allowedSorts(
                'name_en',
                'name_fr',
                AllowedSort::custom('name-en-length', new StringLengthSort, 'name_en'),
                AllowedSort::custom('name-fr-length', new StringLengthSort, 'name_fr'),
            )
            ->allowedFilters(
                AllowedFilter::partial('name_en'),
                AllowedFilter::partial('name_fr'),
                AllowedFilter::custom('search', new MultiColumnFilter('name_en', 'name_fr')),
                AllowedFilter::scope('used')
            );
    }
}
