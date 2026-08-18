<?php

declare(strict_types=1);

namespace App\Exports;

use App\Queries\PublicationListQuery;
use Maatwebsite\Excel\Concerns\Export;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PublicationsExport implements Export, WithMultipleSheets
{
    use Exportable;

    public function __construct(private readonly PublicationListQuery $query) {}

    public function sheets(): array
    {
        return [
            new PublicationsSheet($this->query),
            new PublicationAuthorsSheet($this->query),
        ];
    }
}
