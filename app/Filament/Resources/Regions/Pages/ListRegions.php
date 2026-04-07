<?php

declare(strict_types=1);

namespace App\Filament\Resources\Regions\Pages;

use App\Filament\Resources\Regions\Regions\RegionResource;
use Filament\Resources\Pages\ListRecords;

class ListRegions extends ListRecords
{
    protected static string $resource = RegionResource::class;
}
