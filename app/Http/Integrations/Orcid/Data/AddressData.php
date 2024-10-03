<?php

namespace App\Http\Integrations\Orcid\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class AddressData extends Data
{
    public function __construct(
        public string $city,
        public string|Optional $region,
        public string $country
    ) {}
}
