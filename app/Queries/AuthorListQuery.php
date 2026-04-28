<?php

namespace App\Queries;

use App\Filters\FuzzyFilter;
use App\Models\Author;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

/** @extends QueryBuilder<Author> */
class AuthorListQuery extends QueryBuilder
{
    public function __construct()
    {
        parent::__construct(Author::query());

        $this
            ->defaultSort('last_name')
            ->allowedSorts('first_name', 'last_name', 'email')
            ->allowedIncludes('organization', 'expertises')
            ->allowedFilters(
                AllowedFilter::exact('id'),
                AllowedFilter::partial('first_name'),
                AllowedFilter::partial('last_name'),
                AllowedFilter::partial('email'),
                AllowedFilter::exact('organization_id'),
                AllowedFilter::exact('orcid'),
                AllowedFilter::callback('expertise_id', function (Builder $query, $value): void {
                    $query->whereHas('expertises', function (Builder $query) use ($value): void {
                        $query->whereKey($value);
                    });
                }),
                AllowedFilter::custom('search', new FuzzyFilter('first_name', 'last_name', 'email')),
                AllowedFilter::scope('internal_author'),
                AllowedFilter::scope('external_author'),
                AllowedFilter::scope('with_orcid'),
            );
    }
}
