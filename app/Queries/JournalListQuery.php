<?php

namespace App\Queries;

use App\Filters\FuzzyFilter;
use App\Models\Journal;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class JournalListQuery extends QueryBuilder
{
    public function __construct()
    {
        parent::__construct(Journal::query());

        $this
        ->defaultSort('title_en')
        ->allowedSorts('title_en', 'title_fr', 'publisher')
        ->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::partial('title_en'),
            AllowedFilter::partial('title_fr'),
            AllowedFilter::custom('search', new FuzzyFilter('title_en', 'title_fr')),
            AllowedFilter::scope('dfo_series'),
        ]);
    }
}
