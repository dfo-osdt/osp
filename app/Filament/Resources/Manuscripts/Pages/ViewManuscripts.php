<?php

namespace App\Filament\Resources\Manuscripts\Pages;

use App\Filament\Resources\Manuscripts\ManuscriptsResource;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewManuscripts extends ViewRecord
{
    protected static string $resource = ManuscriptsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Manuscripts')
                ->url(fn (): string => ManuscriptsResource::getUrl('index'))
                ->icon('heroicon-o-arrow-small-left')
                ->color('warning'),
            EditAction::make(),
            Action::make('delete')
                ->label('Delete')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->disabled(fn ($record) => ! auth()->user()->can('delete', $record))
                ->action(function ( $record) {
                    DeleteManuscriptRecord::handle($record);
                }),
        ];
    }
}
