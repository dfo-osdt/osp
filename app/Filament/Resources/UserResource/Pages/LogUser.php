<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\ViewRecord;

class LogUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.resources.user-resource.pages.log-user';

    protected static ?string $title = 'Show User Logs';

    protected function getHeaderWidgets(): array
    {
        return [
            UserResource\Widgets\ActivityLogView::class,
        ];
    }
}
