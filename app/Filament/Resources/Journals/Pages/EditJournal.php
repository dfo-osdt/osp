<?php

declare(strict_types=1);

namespace App\Filament\Resources\Journals\Pages;

use App\Filament\Resources\Journals\JournalResource;
use App\Jobs\SyncReviewContributionWithOrcid;
use App\Models\Journal;
use App\Models\ManuscriptReviewContributor;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Locked;

class EditJournal extends EditRecord
{
    #[\Override]
    protected static string $resource = JournalResource::class;

    /**
     * Whether the admin confirmed deleting the ORCID items of the journal's reports,
     * only ever set by the confirmation action
     */
    #[Locked]
    public bool $orcidSyncTurnOffConfirmed = false;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * Turning off ORCID sync deletes the peer reviews already on authors' ORCID
     * records: ask before saving.
     */
    protected function beforeSave(): void
    {
        if ($this->orcidSyncTurnOffConfirmed || ! $this->turnsOffOrcidSync() || $this->contributionsOnOrcid()->doesntExist()) {
            return;
        }

        $this->mountAction('turnOffOrcidSync');

        $this->halt();
    }

    public function turnOffOrcidSyncAction(): Action
    {
        return Action::make('turnOffOrcidSync')
            ->requiresConfirmation()
            ->color('danger')
            ->modalIcon(Heroicon::OutlinedExclamationTriangle)
            ->modalHeading('Turn off ORCID sync?')
            ->modalDescription(function (): string {
                $count = $this->contributionsOnOrcid()->count();

                return ($count === 1
                    ? '1 review contribution from this series is on an author\'s ORCID record. It will be deleted from ORCID.'
                    : "{$count} review contributions from this series are on authors' ORCID records. They will all be deleted from ORCID.")
                    .' Turning sync back on adds them again.';
            })
            ->modalSubmitActionLabel('Turn off and delete from ORCID')
            ->action(function (): void {
                $onOrcid = $this->contributionsOnOrcid()->count();

                try {
                    $this->orcidSyncTurnOffConfirmed = true;
                    $this->save();
                } finally {
                    $this->orcidSyncTurnOffConfirmed = false;
                }

                activity()
                    ->performedOn($this->getRecord())
                    ->causedBy(auth()->user())
                    ->withProperties(['contributions_on_orcid' => $onOrcid])
                    ->log('journal.orcid-sync-turned-off');
            });
    }

    /**
     * Whether the form, as filled, turns off ORCID sync: unticked, or the
     * journal is no longer a DFO series (see mutateFormDataBeforeSave())
     */
    private function turnsOffOrcidSync(): bool
    {
        /** @var Journal $journal */
        $journal = $this->getRecord();

        return $journal->orcid_sync_enabled
            && (! ($this->data['orcid_sync_enabled'] ?? false) || ($this->data['publisher'] ?? null) !== Journal::$dfoPublisher);
    }

    /**
     * @return Builder<ManuscriptReviewContributor>
     */
    private function contributions(): Builder
    {
        return ManuscriptReviewContributor::query()
            ->whereHas('manuscriptRecord.publication', fn (Builder $query): Builder => $query->where('journal_id', $this->getRecord()->getKey()));
    }

    /**
     * @return Builder<ManuscriptReviewContributor>
     */
    private function contributionsOnOrcid(): Builder
    {
        return $this->contributions()->whereNotNull('orcid_putcode');
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

    /**
     * The ORCID peer reviews of the journal's reports depend on whether it counts
     * for ORCID, its ISSN (the review group) and its title: bring them in line.
     */
    protected function afterSave(): void
    {
        /** @var Journal $journal */
        $journal = $this->getRecord();

        if (! $journal->wasChanged(['orcid_sync_enabled', 'issn', 'title'])) {
            return;
        }

        SyncReviewContributionWithOrcid::dispatchForChanged($this->contributions()->get());
    }
}
