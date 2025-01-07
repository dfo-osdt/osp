<?php

namespace App\Filament\Resources\AnnouncementResource\Pages;

use App\Filament\Resources\AnnouncementResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAnnouncement extends CreateRecord
{
    protected static string $resource = AnnouncementResource::class;

    protected static bool $canCreateAnother = false;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
	$data['end_at'] = $data['end_at'] . 'T23:59:59';

	return $data;
    }
}
