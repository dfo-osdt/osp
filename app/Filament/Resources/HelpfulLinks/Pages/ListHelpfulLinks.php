<?php

namespace App\Filament\Resources\HelpfulLinks\Pages;

use App\Filament\Resources\HelpfulLinks\HelpfulLinkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHelpfulLinks extends ListRecords
{
    protected static string $resource = HelpfulLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
