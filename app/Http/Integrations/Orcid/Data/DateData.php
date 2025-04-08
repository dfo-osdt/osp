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
     */
    public static function fromString(string $date): self
    {
        $dateParts = explode('-', $date);

        if (count($dateParts) !== 3) {
            throw new \InvalidArgumentException('Invalid date format. Expected YYYY-MM-DD.');
        }

        return new self(
            year: $dateParts[0],
            month: $dateParts[1],
            day: $dateParts[2]
        );
    }
}
