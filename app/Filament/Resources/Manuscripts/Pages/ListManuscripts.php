<?php

namespace App\Filament\Resources\Manuscripts\Pages;

use App\Enums\ManuscriptRecordStatus;
use App\Filament\Resources\Manuscripts\ManuscriptsResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

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
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', $config['status']))
                ->badge(fn (): int => $this->getTabCount($config['status']));
        }

        return $tabs;
    }

    protected function getStatusTabs(): array
    {
        return [
            'drafts' => ['label' => 'Drafts', 'status' => ManuscriptRecordStatus::DRAFT->value],
            'in_review' => ['label' => 'In-Review', 'status' => ManuscriptRecordStatus::IN_REVIEW->value],
            'reviewed' => ['label' => 'Reviewed', 'status' => ManuscriptRecordStatus::REVIEWED->value],
            'accepted' => ['label' => 'Accepted', 'status' => ManuscriptRecordStatus::ACCEPTED->value],
            'withdrawn' => ['label' => 'Withdrawn', 'status' => ManuscriptRecordStatus::WITHDRAWN->value],
        ];
    }

    protected function getTabCount(?string $status = null): int
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
            $query->where('status', $status);
        }

        return $query->count();
    }
}
