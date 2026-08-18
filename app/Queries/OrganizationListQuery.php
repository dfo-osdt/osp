<?php

namespace App\Queries;

use App\Filters\MultiColumnFilter;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

/** @extends QueryBuilder<Organization> */
class OrganizationListQuery extends QueryBuilder
{
    public function __construct(Builder|Relation|null $subject = null, ?Request $request = null)
    {
        parent::__construct($subject ?? Organization::query(), $request);

        $this
            ->defaultSort('name_'.app()->getLocale())
            ->allowedSorts(
                'name_en',
                'name_fr',
                'country_code',
                AllowedSort::custom('name-fr-length', new StringLengthSort, 'name_fr'),
                AllowedSort::custom('name-en-length', new StringLengthSort, 'name_en'),
            )
            ->allowedFilters(
                AllowedFilter::exact('id'),
                AllowedFilter::exact('country_code'),
                AllowedFilter::partial('ror_identifier'),
                AllowedFilter::partial('name_en'),
                AllowedFilter::partial('name_fr'),
                AllowedFilter::custom('search', new MultiColumnFilter('abbr_en', 'abbr_fr', 'name_en', 'name_fr')),
            );
    }
}
