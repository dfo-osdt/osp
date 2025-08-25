<?php

namespace App\Filament\Resources\Authentications\Pages;

use App\Filament\Resources\Authentications\Authentications\AuthenticationResource;
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
