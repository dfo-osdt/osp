<?php

namespace App\Http\Integrations\Orcid\Data;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

class ExternalIdsData extends Data
{
    /**
     * @param  ExternalIdData[]  $externalId;
     */
    public function __construct(
        #[MapName('external-id')]
        public array $externalId
    ) {}
}
