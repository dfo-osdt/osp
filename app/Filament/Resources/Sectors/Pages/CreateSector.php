<?php

declare(strict_types=1);

namespace App\Filament\Resources\Sectors\Pages;

use App\Filament\Resources\Sectors\SectorResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSector extends CreateRecord
{
    #[\Override]
    protected static string $resource = SectorResource::class;
}
