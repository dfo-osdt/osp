<?php

namespace App\Filament\Resources\Orcid;

use App\Contracts\HasOrcidSyncStatus;
use App\Enums\OrcidSyncStatus;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;

/**
 * Counts by ORCID sync status of the records in an ORCID audit table, following its filters.
 */
abstract class OrcidSyncStatusStats extends StatsOverviewWidget
{
    use InteractsWithPageTable;

    /**
     * The statuses that apply to the item type, in display order
     *
     * @return array<int, OrcidSyncStatus>
     */
    abstract protected function statuses(): array;

    protected function getStats(): array
    {
        $counts = $this->getPageTableQuery()->get()
            ->countBy(fn (Model $record): string => $record instanceof HasOrcidSyncStatus ? $record->orcidSyncStatus()->value : '');

        return collect($this->statuses())
            ->map(fn (OrcidSyncStatus $status): Stat => Stat::make($status->getLabel(), $counts->get($status->value, 0))
                ->color($status->getColor()))
            ->all();
    }
}
