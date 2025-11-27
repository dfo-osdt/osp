<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\Users\UserResource;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity;

class ViewUserLogs extends ManageRelatedRecords
{
    protected static string $resource = UserResource::class;

    protected static string $relationship = 'actions';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'View Activities';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Back')
                ->url(fn (): string => UserResource::getUrl('index'))
                ->icon('heroicon-o-arrow-small-left'),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('causer_id')
            ->columns([
                TextColumn::make('created_at')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('event')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subject_type')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subject_id')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('log_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('causer_id')
                    ->hidden(),
            ])
            ->filters([
                Filter::make('created_at')
                    ->schema([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until')
                            ->default(now()),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when(
                            $data['created_from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['created_until'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        ))
                    ->columns(2),
                SelectFilter::make('event')
                    ->options(
                        fn () => Activity::query()
                            ->whereNotNull('event')
                            ->distinct()
                            ->pluck('event', 'event')
                            ->toArray()
                    ),
                SelectFilter::make('subject_type')
                    ->options(
                        fn () => Activity::query()
                            ->whereNotNull('subject_type')
                            ->distinct()
                            ->pluck('subject_type', 'subject_type')
                            ->toArray()
                    ),
                Filter::make('no_event')
                    ->label('No Event')
                    ->query(fn ($query) => $query->whereNull('event')),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->recordActions([
                ViewAction::make(),
            ])
            ->defaultSort('created_at', $direction = 'desc');
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        KeyValueEntry::make('attributes')
                            ->label('Log Entry (JSON)')
                            ->default(fn ($record) => $record->getAttributes()),
                    ]),
            ]);
    }
}
