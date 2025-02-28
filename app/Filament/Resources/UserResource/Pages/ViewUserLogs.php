<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
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
        return 'View Logs';
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
                        ->options(fn () => Activity::query()
                            ->whereNotNull('event')
                            ->distinct()
                            ->pluck('event','event')
                            ->toArray()
                ),
                    Tables\Filters\SelectFilter::make('subject_type')
                        ->options(fn () => Activity::query()
                            ->whereNotNull('subject_type')
                            ->distinct()
                            ->pluck('subject_type','subject_type')
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
                Components\Section::make('Event Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('id')
                            ->label('Event Id'),
                        Infolists\Components\TextEntry::make('created_at'),
                        Infolists\Components\TextEntry::make('updated_at'),
                        Infolists\Components\TextEntry::make('event')
                            ->label('Event type'),
                        Infolists\Components\TextEntry::make('log_name'),
                    ])
                    ->columns(3),
                Components\Section::make('Causer Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('causer_id')
                            ->label('Id'),
                        Infolists\Components\TextEntry::make('causer_type')
                            ->label('Type'),
                        Infolists\Components\TextEntry::make('causer.email')
                            ->label('Email'),
                    ])
                    ->columns(3),
                Components\Section::make('Subject Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('subject_id')
                            ->label('Id'),
                        Infolists\Components\TextEntry::make('subject_type')
                            ->label('Type'),
                    ])
                    ->columns(3),
                Components\Section::make()
                    ->schema([
                        Infolists\Components\TextEntry::make('description'),
                    ]),
                Components\Section::make()
                ->schema([
                    Infolists\Components\KeyValueEntry::make('attributes')
                        ->label('Log Entry (JSON)')
                        ->default(fn ($record) => $record->getAttributes()),
                    ]),
            ]);
    }
}
