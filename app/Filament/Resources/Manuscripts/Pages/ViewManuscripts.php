<?php

namespace App\Filament\Resources\Manuscripts\Pages;

use App\Actions\DeleteSubmittedManuscriptRecord;
use App\Enums\ManuscriptRecordStatus;
use App\Filament\Resources\Manuscripts\ManuscriptsResource;
use App\Models\ManuscriptRecord;
use Filament\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;

class ViewManuscripts extends ViewRecord implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = ManuscriptsResource::class;

    protected string $view = 'filament.resources.manuscripts.pages.view-manuscripts';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Manuscripts')
                ->url(fn (): string => ManuscriptsResource::getUrl('index'))
                ->icon('heroicon-o-arrow-small-left')
                ->color('warning'),
            Action::make('delete')
                ->label('Delete In-Review')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn ($record): bool => $record->status === ManuscriptRecordStatus::IN_REVIEW)
                ->action(function () {
                    $record = $this->getRecord();

                    if (! $record instanceof ManuscriptRecord) {
                        throw new \RuntimeException('Expected a ManuscriptRecord.');
                    }

                    try {
                        DeleteSubmittedManuscriptRecord::handle($record);

                        Notification::make()
                            ->title('Manuscript deleted')
                            ->success()
                            ->send();

                        return $this->redirect(ManuscriptsResource::getUrl('index'));

                    } catch (\InvalidArgumentException $e) {
                        Notification::make()
                            ->title('Unable to delete manuscript')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }

    public function mount(int|string $record): void
    {
        parent::mount($record);

        $this->record->loadMissing([
            'managementReviewSteps',
            'manuscriptAuthors.author',
            'shareables',
            'region',
            'user',
        ]);

        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components(ManuscriptsResource::manuscriptFormSchema(disabled: true))
            ->model($this->record)
            ->statePath('data');
    }
}
