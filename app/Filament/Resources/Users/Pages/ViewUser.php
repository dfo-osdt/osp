<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\Users\UserResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Back')
                ->url(fn () => UserResource::getUrl('index'))
                ->icon('heroicon-o-arrow-small-left'),
        ];
    }
}
