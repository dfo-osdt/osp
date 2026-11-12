<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orcid\Works\Widgets;

use App\Filament\Resources\Orcid\OrcidSyncStatusStats;
use App\Filament\Resources\Orcid\Works\Pages\ListWorks;
use App\Filament\Resources\Orcid\Works\WorkResource;

class WorkStats extends OrcidSyncStatusStats
{
    protected function getTablePage(): string
    {
        return ListWorks::class;
    }

    protected function statuses(): array
    {
        return WorkResource::statuses();
    }
}
