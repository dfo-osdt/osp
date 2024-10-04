<?php

namespace App\Http\Integrations\Orcid\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;

class TimestampData extends Data
{
    #[Computed]
    public Carbon $timestamp;

    public function __construct(
        public int $value
    ) {
        $this->timestamp = Carbon::createFromTimestampMs($this->value);
    }
}
