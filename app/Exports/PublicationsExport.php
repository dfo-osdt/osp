<?php

namespace App\Exports;

use App\Queries\PublicationListQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PublicationsExport implements WithMultipleSheets
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
