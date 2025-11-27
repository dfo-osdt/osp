<?php

namespace App\Filament\Resources\ActivityLogs\ActivityLogs;

use App\Filament\Resources\ActivityLogs\Pages\ListActivityLogs;
use App\Filament\Resources\ActivityLogs\Pages\ViewActivityLog;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Audit Logs';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('event')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('causer.email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subject_type')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('log_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subject_id')
                    ->hidden()
                    ->searchable(),
                TextColumn::make('causer_id')
                    ->hidden()
                    ->searchable(),
            ])
            ->filters([
                Filter::make('created_at')
                    ->schema([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until')
                            ->default(now()),
                    ])
                    ->query(fn(Builder $query, array $data): Builder => $query
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
                SelectFilter::make('causer_type')
                    ->options(
                        fn () => Activity::query()
                            ->whereNotNull('causer_type')
                            ->distinct()
                            ->pluck('causer_type', 'causer_type')
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

    public static function infolist(Schema $schema): Schema
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

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivityLogs::route('/'),
            'view' => ViewActivityLog::route('/{record}'),
        ];
    }
}
