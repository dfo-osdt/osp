<?php

declare(strict_types=1);

namespace App\Filament\Resources\Announcements\Pages;

use App\Filament\Resources\Announcements\Announcements\AnnouncementResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAnnouncement extends CreateRecord
{
    #[\Override]
    protected static string $resource = AnnouncementResource::class;

    #[\Override]
    protected static bool $canCreateAnother = false;
}
