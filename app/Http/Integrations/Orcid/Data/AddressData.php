<?php
namespace App\Http\Integrations\Orcid\Data;

use Spatie\LaravelData\Data;

class AddressData extends Data
{
    public function __construct(
        public string $city,
        public string $region,
        public string $country
    ) {}
}