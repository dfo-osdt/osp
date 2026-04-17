<?php

namespace App\Filament\Resources\Manuscripts\Pages;

use App\Enums\ManuscriptRecordStatus;
use App\Filament\Resources\Manuscripts\ManuscriptsResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;

class ListManuscripts extends ListRecords
{
    protected static string $resource = ManuscriptsResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make()
                ->badge(fn (): int => $this->getTabCount()),
        ];

        foreach ($this->getStatusTabs() as $key => $config) {
            $tabs[$key] = Tab::make($config['label'])
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', $config['status']->value))
                ->badge(fn (): int => $this->getTabCount($config['status']));
        }

        return $tabs;
    }

    protected function getStatusTabs(): array
    {
        return collect([
            ManuscriptRecordStatus::DRAFT,
            ManuscriptRecordStatus::IN_REVIEW,
            ManuscriptRecordStatus::REVIEWED,
            ManuscriptRecordStatus::ACCEPTED,
            ManuscriptRecordStatus::WITHDRAWN,
        ])
            ->mapWithKeys(fn (ManuscriptRecordStatus $status): array => [
                $status->value => [
                    'label' => Str::headline($status->value),
                    'status' => $status,
                ],
            ])
            ->all();
    }

    protected function getTabCount(?ManuscriptRecordStatus $status = null): int
    {
        $query = $this->getTableQuery();

        if ($query instanceof Relation) {
            $query = $query->getQuery();
        }

        if (! $query instanceof Builder) {
            return 0;
        }

        $query = $this->filterTableQuery($query);

        if ($status !== null) {
            $query->where('status', $status->value);
        }

        return $query->count();
    }
}
