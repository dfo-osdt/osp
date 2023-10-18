<?php

namespace App\Queries;

use App\Filters\MultiColumnFilter;
use App\Models\Expertise;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

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
                AllowedSort::custom('name-length', new StringLengthSort(), 'name_en'),
            ])
            ->allowedFilters(([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('tid'),
                AllowedFilter::partial('name_en'),
                AllowedFilter::partial('name_fr'),
                AllowedFilter::custom('search', new MultiColumnFilter('name_en', 'name_fr')),
                AllowedFilter::scope('main_expertise'),
            ]));
    }
}
