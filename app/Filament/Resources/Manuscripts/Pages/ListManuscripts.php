<?php

namespace App\Filament\Resources\Manuscripts\Pages;

use App\Filament\Resources\Manuscripts\ManuscriptsResource;
use Filament\Resources\Pages\ListRecords;

class ListManuscripts extends ListRecords
{
    protected static string $resource = ManuscriptsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
