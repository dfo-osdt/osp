<?php

namespace App\Queries;

use App\Filters\FuzzyFilter;
use App\Models\Author;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class AuthorListQuery extends QueryBuilder
{
    public function __construct()
    {
        parent::__construct(Author::query());

        $this
            ->defaultSort('last_name')
            ->allowedSorts('first_name', 'last_name', 'email')
            ->allowedIncludes('organization')
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('first_name'),
                AllowedFilter::partial('last_name'),
                AllowedFilter::partial('email'),
                AllowedFilter::exact('organization_id'),
                AllowedFilter::exact('orcid'),
                AllowedFilter::custom('search', new FuzzyFilter('first_name', 'last_name', 'email')),
            ]);
    }
}
