<?php

declare(strict_types=1);

namespace App\Filament\Resources\Announcements\Pages;

use App\Filament\Resources\Announcements\Announcements\AnnouncementResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAnnouncement extends ViewRecord
{
    protected static string $resource = AnnouncementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
