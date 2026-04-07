<?php

declare(strict_types=1);

namespace App\Http\Integrations\Orcid\Data;

use Spatie\LaravelData\Data;

class UrlData extends Data
{
    public function __construct(
        public string $value,
    ) {}
}
