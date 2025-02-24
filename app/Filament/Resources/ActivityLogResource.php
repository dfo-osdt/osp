<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('event')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('causer.email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject_type')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('log_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject_id')
                    ->hidden(),
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
                    ->multiple()
                    ->options(fn () => Activity::query()
                        ->select('event')->selectRaw('COALESCE(event, "No Event") as event')->distinct()->pluck('event', 'event')->toArray()), // handle nulls
                Tables\Filters\SelectFilter::make('causer_type')
                    ->options(fn () => Activity::query()
                        ->select('causer_type')->selectRaw('COALESCE(causer_type, "No Causer Type") as causer_type')->distinct()->pluck('causer_type', 'causer_type')->toArray()),
                Tables\Filters\SelectFilter::make('subject_type')
                    ->options(fn () => Activity::query()
                        ->select('causer_type')->selectRaw('COALESCE(subject_type, "No Subject Type") as subject_type')->distinct()->pluck('subject_type', 'subject_type')->toArray()),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // ,
                ]),
            ])
            ->defaultSort('created_at', $direction = 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
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
                    ->columns(2),
                Components\Section::make('Subject Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('subject_id')
                            ->label('Id'),
                        Infolists\Components\TextEntry::make('subject_type')
                            ->label('Type'),
                        Infolists\Components\TextEntry::make('subject.email')
                            ->label('Email'),
                    ])
                    ->columns(3),
                Components\Section::make()
                    ->schema([
                        Infolists\Components\TextEntry::make('description'),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
            'view' => Pages\ViewActivityLog::route('/{record}'),
        ];
    }
}
