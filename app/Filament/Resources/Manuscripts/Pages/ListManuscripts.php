<?php

namespace App\Filament\Resources\Manuscripts\Pages;

use App\Filament\Resources\Manuscripts\ManuscriptsResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListManuscripts extends ListRecords
{
    protected static string $resource = ManuscriptsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }

    public function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make()
                ->badge(fn () => $this->getTabCount()),
        ];

        foreach ($this->getStatusTabs() as $key => $label) {
            $tabs[$key] = Tab::make($label)
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', $key))
                ->badge(fn () => $this->getTabCount($key));
        }

        return $tabs;
    }

    protected function getStatusTabs(): array
    {
        return [
            'draft' => 'Drafts',
            'in_review' => 'In-Review',
            'reviewed' => 'Reviewed',
            'accepted' => 'Accepted',
        ];
    }

    protected function getTabCount(?string $status = null): int
    {
        $query = static::getResource()::getEloquentQuery();

        if ($status !== null) {
            $query->where('status', $status);
        }

        $this->applyActiveFiltersToQuery($query);

        return $query->count();
    }

    protected function applyActiveFiltersToQuery(Builder $query): void
    {
        $type = data_get($this->tableFilters, 'type.value');
        $region = data_get($this->tableFilters, 'region.value');

        if (filled($type)) {
            $query->where('type', $type);
        }

        if (filled($region)) {
            $query->whereHas('region', fn (Builder $q) => $q->whereKey($region));
        }
    }
}
