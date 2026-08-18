<?php

namespace App\Queries;

use App\Filters\MultiColumnFilter;
use App\Models\Journal;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

/** @extends QueryBuilder<Journal> */
class JournalListQuery extends QueryBuilder
{
    public function __construct(Builder|Relation|null $subject = null, ?Request $request = null)
    {
        parent::__construct($subject ?? Journal::query(), $request);

        $this
            ->defaultSort('title')
            ->allowedSorts(
                'title',
                'publisher',
                AllowedSort::custom('title-length', new StringLengthSort, 'title'),
            )
            ->allowedFilters(
                AllowedFilter::exact('id'),
                AllowedFilter::partial('title'),
                AllowedFilter::custom('search', new MultiColumnFilter('title', 'issn')),
                AllowedFilter::scope('dfo_series'),
                AllowedFilter::scope('not_dfo_series'),
            );
    }
}
