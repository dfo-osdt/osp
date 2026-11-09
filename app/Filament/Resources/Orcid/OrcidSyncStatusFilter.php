<?php

namespace App\Filament\Resources\Orcid;

use App\Contracts\HasOrcidSyncStatus;
use App\Enums\OrcidSyncStatus;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Filters an ORCID audit table by sync status. The status is worked out in PHP
 * from each record (see the models' orcidSyncStatus()), so the matching records
 * are found in the table's full query first, then the query is narrowed to them.
 * The query a filter receives is a bare nested where, without the table's
 * constraints and eager loads.
 */
class OrcidSyncStatusFilter
{
    /**
     * @param  array<int, OrcidSyncStatus>  $statuses  The statuses that apply to the item type
     */
    public static function make(array $statuses): SelectFilter
    {
        return SelectFilter::make('orcid_sync_status')
            ->label('Status')
            ->options(collect($statuses)->mapWithKeys(fn (OrcidSyncStatus $status): array => [$status->value => $status->getLabel()])->all())
            ->query(function (Builder $query, array $data, SelectFilter $filter): Builder {
                $status = OrcidSyncStatus::tryFrom((string) $data['value']);

                if ($status === null) {
                    return $query;
                }

                $matching = $filter->getTable()->getQuery()->get()
                    ->filter(fn (Model $record): bool => $record instanceof HasOrcidSyncStatus && $record->orcidSyncStatus() === $status)
                    ->modelKeys();

                return $query->whereKey($matching);
            });
    }
}
