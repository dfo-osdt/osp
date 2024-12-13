<?php

namespace App\Queries;

use App\Filters\MultiColumnFilter;
use App\Models\Journal;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class JournalListQuery extends QueryBuilder
{
    public function __construct()
    {
        parent::__construct(Journal::query());

        $this
            ->defaultSort('title')
            ->allowedSorts([
                'title',
                'publisher',
                AllowedSort::custom('title-length', new StringLengthSort, 'title'),
            ])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('title'),
                AllowedFilter::custom('search', new MultiColumnFilter('title', 'issn')),
                AllowedFilter::scope('dfo_series'),
            ]);
    }
}
