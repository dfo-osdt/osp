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

        foreach ($this->getStatusTabs() as $key => $config) {
            $tabs[$key] = Tab::make($config['label'])
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', $config['status']))
                ->badge(fn () => $this->getTabCount($config['status']));
        }

        return $tabs;
    }

    protected function getStatusTabs(): array
    {
        return [
            'drafts' => ['label' => 'Drafts', 'status' => 'draft'],
            'in_review' => ['label' => 'In-Review', 'status' => 'in_review'],
            'reviewed' => ['label' => 'Reviewed', 'status' => 'reviewed'],
            'accepted' => ['label' => 'Accepted', 'status' => 'accepted'],
            'withdrawn' => ['label' => 'Withdrawn', 'status' => 'withdrawn'],
        ];
    }

    protected function getTabCount(?string $status = null): int
    {
        $query = clone $this->getFilteredTableQuery();

        if ($status !== null) {
            $query->where('status', $status);
        }

        return $query->count();
    }
}
