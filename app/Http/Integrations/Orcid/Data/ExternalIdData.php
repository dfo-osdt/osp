<?php

namespace App\Http\Integrations\Orcid\Data;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

class ExternalIdData extends Data
{
    public function __construct(
        #[MapName('external-id-type')]
        public string $externalIdType,

        #[MapName('external-id-value')]
        public string $externalIdValue,

        #[MapName('external-id-url')]
        public UrlData $externalIdUrl,

        #[MapName('external-id-relationship')]
        public string $externalIdRelationship
    ) {}
}
