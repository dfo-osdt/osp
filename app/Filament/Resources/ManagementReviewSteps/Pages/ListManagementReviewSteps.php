<?php

namespace App\Filament\Resources\ManagementReviewSteps\Pages;

use App\Filament\Resources\ManagementReviewSteps\ManagementReviewStepsResource;
use Filament\Resources\Pages\ListRecords;

class ListManagementReviewSteps extends ListRecords
{
    protected static string $resource = ManagementReviewStepsResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
