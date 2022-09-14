<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait PaginationLimitTrait
{
    /**
     * Get the limit from the request
     *
     * @param Request request with "limit" value
     * @return int
     */
    public function getLimitFromRequest(Request $request, int $defaultLimit = 10, int $max = 100): int
    {
        $validated = $request->validate([
            'limit' => ['integer', 'min:1', 'max:'.$max],
        ]);

        $limit = $validated['limit'] ?? $defaultLimit;
        $limit = min($limit, $max);

        return $limit;
    }
}
