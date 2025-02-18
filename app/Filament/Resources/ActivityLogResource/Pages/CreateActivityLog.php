<?php

namespace App\Filament\Resources\ActivityLogResource\Pages;

use App\Filament\Resources\ActivityLogResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateActivityLog extends CreateRecord
{
    protected static string $resource = ActivityLogResource::class;
}
