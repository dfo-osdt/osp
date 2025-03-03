<?php

namespace App\Filament\Resources\AuthenticationResource\Pages;

use App\Filament\Resources\ActivityLogResource;
use App\Filament\Resources\AuthenticationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAuthentications extends ListRecords
{
    protected static string $resource = AuthenticationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Activity Log')->url(fn () => ActivityLogResource::getUrl('index')),
        ];
    }
}
