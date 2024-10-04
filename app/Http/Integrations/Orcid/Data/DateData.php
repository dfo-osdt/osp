<?php

namespace App\Http\Integrations\Orcid\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class DateData extends Data
{
    public function __construct(
        #[MapInputName('year.value')]
        public ?string $year,

        #[MapInputName('month.value')]
        public ?string $month,

        #[MapInputName('day.value')]
        public ?string $day
    ) {}

    /**
     * @param  string  $date  Example: 2021-01-01
     * @return static
     */
    public static function fromString(string $date): self
    {
        $dateParts = explode('-', $date);

        return new self(
            year: $dateParts[0] ?? null,
            month: $dateParts[1] ?? null,
            day: $dateParts[2] ?? null
        );
    }
}
