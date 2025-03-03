<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Infolists;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity;

class ViewUserLogs extends ManageRelatedRecords
{
    protected static string $resource = UserResource::class;

    protected static string $relationship = 'actions';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'View Activities';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Back')->url(fn () => UserResource::getUrl('index')),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('causer_id')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('event')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject_type')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject_id')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('log_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('causer_id')
                    ->hidden(),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until')
                            ->default(now()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->columns(2),
                Tables\Filters\SelectFilter::make('event')
                    ->options(
                        fn () => Activity::query()
                            ->whereNotNull('event')
                            ->distinct()
                            ->pluck('event', 'event')
                            ->toArray()
                    ),
                Tables\Filters\SelectFilter::make('subject_type')
                    ->options(
                        fn () => Activity::query()
                            ->whereNotNull('subject_type')
                            ->distinct()
                            ->pluck('subject_type', 'subject_type')
                            ->toArray()
                    ),
                Tables\Filters\Filter::make('no_event')
                    ->label('No Event')
                    ->query(fn ($query) => $query->whereNull('event')),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->defaultSort('created_at', $direction = 'desc');
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make()
                    ->schema([
                        Infolists\Components\KeyValueEntry::make('attributes')
                            ->label('Log Entry (JSON)')
                            ->default(fn ($record) => $record->getAttributes()),
                    ]),
            ]);
    }
}
