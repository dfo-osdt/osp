<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Spatie\QueryBuilder\Filters\Filter;

class MultiColumnFilter implements Filter
{
    private readonly array $columns;

    public function __construct(...$columns)
    {
        $this->columns = $columns;
    }

    public function __invoke(Builder $query, $value, string $property)
    {
        $attributes = $this->columns;

        // is database engine postgresql? if so, use ILIKE as it is case insensitive
        $like = config('database.default') === 'pgsql' ? 'ILIKE' : 'LIKE';

        $query->where(function (Builder $query) use ($attributes, $value, $like): void {
            foreach (Arr::wrap($attributes) as $attribute) {
                $query->orWhere(function ($query) use ($attribute, $value, $like): void {
                    $query->orWhere($attribute, $like, "%{$value}%");
                });
            }
        });

        return $this;
    }
}
