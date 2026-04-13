<?php

namespace App\Filament\Resources\Manuscripts\Pages;

use App\Filament\Resources\Manuscripts\ManuscriptsResource;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditManuscripts extends EditRecord
{
    protected static string $resource = ManuscriptsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            Action::make('Manuscripts')
                ->url(fn (): string => ManuscriptsResource::getUrl('index'))
                ->icon('heroicon-o-arrow-small-left')
                ->color('warning'),
        ];
    }
}
