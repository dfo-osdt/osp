<?php

namespace App\Http\Integrations\Orcid\Data;

use Spatie\LaravelData\Data;

class AddressData extends Data
{
    public function __construct(
        public string $country,
        public ?string $region = null,
        public ?string $city = null
    ) {}
}
