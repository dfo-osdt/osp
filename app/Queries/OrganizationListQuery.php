<?php

namespace App\Queries;

use App\Filters\FuzzyFilter;
use App\Models\Organization;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class OrganizationListQuery extends QueryBuilder
{
    public function __construct()
    {
        parent::__construct(Organization::query());

        $this
            ->defaultSort('name_'.app()->getLocale())
            ->allowedSorts('name_en', 'name_fr')
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('name_en'),
                AllowedFilter::partial('name_fr'),
                AllowedFilter::custom('search', new FuzzyFilter('name_en', 'name_fr', 'abbr_en', 'abbr_fr')),
            ]);
    }
}
