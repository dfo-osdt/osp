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
        ->defaultSort('name_en')
        ->allowedSorts('name_en', 'name_fr', 'publisher')
        ->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::partial('name_en'),
            AllowedFilter::partial('name_fr'),
            AllowedFilter::custom('search', new FuzzyFilter('name_en', 'name_fr')),
            AllowedFilter::scope('dfo_series'),
        ]);
    }
}
