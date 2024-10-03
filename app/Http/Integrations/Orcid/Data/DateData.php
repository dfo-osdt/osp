<?php

namespace App\Http\Integrations\Orcid\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class DateData extends Data
{
    public function __construct(
        #[MapInputName('year.value')]
        public string $year,

        #[MapInputName('month.value')]
        public string $month,

        #[MapInputName('day.value')]
        public string $day
    ) {}
}
