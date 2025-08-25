<?php

namespace App\Filament\Resources\AuthenticationResource\Pages;

use App\Filament\Resources\ActivityLogResource;
use App\Filament\Resources\AuthenticationResource;
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
