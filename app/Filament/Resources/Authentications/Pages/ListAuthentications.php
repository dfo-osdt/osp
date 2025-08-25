<?php

namespace App\Filament\Resources\Authentications\Pages;

use App\Filament\Resources\ActivityLogs\ActivityLogs\ActivityLogResource;
use App\Filament\Resources\Authentications\Authentications\AuthenticationResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListAuthentications extends ListRecords
{
    protected static string $resource = AuthenticationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Activity Log')
                ->url(fn () => ActivityLogResource::getUrl('index'))
                ->icon('heroicon-o-arrow-small-left'),
        ];
    }
}
