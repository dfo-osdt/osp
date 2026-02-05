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

    public function __invoke(Builder $query, mixed $value, string $property)
    {
        // Only accept strings or arrays, skip if invalid type
        if (! is_string($value) && ! is_array($value)) {
            return $this;
        }

        // Normalize value to always be an array for consistent handling
        $searchTerms = Arr::wrap($value);

        // Filter out empty terms
        $searchTerms = array_filter($searchTerms, fn($term): bool => is_string($term) && trim($term) !== '');

        // If no valid search terms, skip filtering
        if ($searchTerms === []) {
            return $this;
        }

        $query->where(function (Builder $query) use ($searchTerms): void {
            foreach ($searchTerms as $term) {
                $term = trim($term);

                // For each search term, check all columns
                $query->orWhere(function (Builder $query) use ($term): void {
                    foreach ($this->columns as $column) {
                        $query->orWhereLike($column, "%{$term}%");
                    }
                });
            }
        });

        return $this;
    }
}
