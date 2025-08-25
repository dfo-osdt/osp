<?php

namespace App\Filament\Resources\AuthenticationResource\Pages;

use App\Filament\Resources\AuthenticationResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewAuthentication extends ViewRecord
{
    protected static string $resource = AuthenticationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Back')
                ->url(fn () => AuthenticationResource::getUrl('index'))
                ->icon('heroicon-o-arrow-small-left'),
        ];
    }
}
