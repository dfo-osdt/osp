<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orcid\Employments\Widgets;

use App\Filament\Resources\Orcid\Employments\EmploymentResource;
use App\Filament\Resources\Orcid\Employments\Pages\ListEmployments;
use App\Filament\Resources\Orcid\OrcidSyncStatusStats;

class EmploymentStats extends OrcidSyncStatusStats
{
    protected function getTablePage(): string
    {
        return ListEmployments::class;
    }

    protected function statuses(): array
    {
        return EmploymentResource::statuses();
    }
}
