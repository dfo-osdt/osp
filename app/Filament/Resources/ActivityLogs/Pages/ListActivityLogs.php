<?php

namespace App\Filament\Resources\ActivityLogs\Pages;

use App\Filament\Resources\ActivityLogs\ActivityLogs\ActivityLogResource;
use App\Filament\Resources\Authentications\Authentications\AuthenticationResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListActivityLogs extends ListRecords
{
    protected static string $resource = ActivityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Authentication Log')
                ->url(fn () => AuthenticationResource::getUrl('index'))
                ->icon('heroicon-o-arrow-small-left'),
        ];
    }
}
