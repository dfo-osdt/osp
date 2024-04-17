<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait PaginationLimitTrait
{
    /**
     * Get the limit from the request
     *
     * @param Request request with "limit" value
     */
    public function getLimitFromRequest(Request $request, ?int $default = null, ?int $max = null): int
    {
        $default = $default ?? config('osp.api_pagination.default', 10);
        $max = $max ?? config('osp.api_pagination.max', 100);

        $validated = $request->validate([
            'limit' => ['integer', 'min:1', 'max:'.$max],
        ]);

        $limit = $validated['limit'] ?? $default;
        $limit = min($limit, $max);

        return $limit;
    }
}
