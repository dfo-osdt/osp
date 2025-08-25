<?php

namespace App\Filament\Resources\Announcements\Pages;

use App\Filament\Resources\Announcements\Announcements\AnnouncementResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAnnouncement extends CreateRecord
{
    protected static string $resource = AnnouncementResource::class;

    protected static bool $canCreateAnother = false;
}
