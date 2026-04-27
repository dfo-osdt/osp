<?php

declare(strict_types=1);

namespace App\Filament\Resources\HelpfulLinks\Pages;

use App\Filament\Resources\HelpfulLinks\HelpfulLinkResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHelpfulLink extends EditRecord
{
    protected static string $resource = HelpfulLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
