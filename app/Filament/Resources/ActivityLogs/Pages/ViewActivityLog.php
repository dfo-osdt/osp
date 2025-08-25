<?php

namespace App\Filament\Resources\ActivityLogs\Pages;

use App\Filament\Resources\ActivityLogs\ActivityLogs\ActivityLogResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewActivityLog extends ViewRecord
{
    protected static string $resource = ActivityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Back')
                ->url(fn () => ActivityLogResource::getUrl('index'))
                ->icon('heroicon-o-arrow-small-left'),
        ];
    }
}
