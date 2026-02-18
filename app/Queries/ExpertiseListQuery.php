<?php

namespace App\Queries;

use App\Filters\MultiColumnFilter;
use App\Models\Expertise;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

/** @extends QueryBuilder<Expertise> */
class ExpertiseListQuery extends QueryBuilder
{
    public function __construct()
    {
        parent::__construct(Expertise::query());

        $this
            ->defaultSort('name_en')
            ->allowedSorts([
                'name_en',
                'name_fr',
                AllowedSort::custom('name-en-length', new StringLengthSort, 'name_en'),
                AllowedSort::custom('name-fr-length', new StringLengthSort, 'name_fr'),
            ])
            ->allowedFilters(([
                AllowedFilter::partial('name_en'),
                AllowedFilter::partial('name_fr'),
                AllowedFilter::custom('search', new MultiColumnFilter('name_en', 'name_fr')),
            ]));
    }
}
