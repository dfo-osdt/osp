<?php

namespace App\Filament\Resources\ActivityLogResource\Pages;

use App\Filament\Resources\ActivityLogResource;
use App\Filament\Resources\AuthenticationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListActivityLogs extends ListRecords
{
    protected static string $resource = ActivityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Authentication Log')
                ->url(fn () => AuthenticationResource::getUrl('index'))
                ->icon('heroicon-o-arrow-small-left'),
        ];
    }
}
