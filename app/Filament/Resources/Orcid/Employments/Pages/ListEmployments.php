<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orcid\Employments\Pages;

use App\Filament\Resources\Orcid\Employments\EmploymentResource;
use App\Filament\Resources\Orcid\Employments\Widgets\EmploymentStats;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListEmployments extends ListRecords
{
    use ExposesTableToWidgets;

    #[\Override]
    protected static string $resource = EmploymentResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            EmploymentStats::class,
        ];
    }
}
