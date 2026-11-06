<?php

declare(strict_types=1);

namespace App\Filament\Resources\Journals\Pages;

use App\Filament\Resources\Journals\JournalResource;
use App\Models\Journal;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditJournal extends EditRecord
{
    #[\Override]
    protected static string $resource = JournalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * The ORCID sync toggle is hidden, so not saved, for non-DFO journals:
     * turn it off explicitly when a series stops being a DFO series.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    #[\Override]
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['publisher'] !== Journal::$dfoPublisher) {
            $data['orcid_sync_enabled'] = false;
        }

        return $data;
    }
}
