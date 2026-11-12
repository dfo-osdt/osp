<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orcid\Works\Pages;

use App\Filament\Resources\Orcid\Works\Widgets\WorkStats;
use App\Filament\Resources\Orcid\Works\WorkResource;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListWorks extends ListRecords
{
    use ExposesTableToWidgets;

    #[\Override]
    protected static string $resource = WorkResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            WorkStats::class,
        ];
    }
}
