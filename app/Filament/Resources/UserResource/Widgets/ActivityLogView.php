<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Filament\Resources\ActivityLogResource;
use Filament\Infolists;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ActivityLogView extends BaseWidget
{
    public ?Model $record = null;

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(ActivityLogResource::getEloquentQuery())
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
                Filter::make('subject_id')
                    ->query(fn (Builder $query): Builder => $query->where('subject_id', $this->record->id)
                        ->orwhere('causer_id', $this->record->id))
                    ->default(),
            ])
            ->hiddenFilterIndicators()
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading('Activity Log Details')
                    ->modalContent(fn ($record) => $this->infolist($record)),
            ])
            ->defaultSort('created_at', $direction = 'desc');
    }

    public static function infolist($record): Infolist
    {
        return Infolists\Infolist::make()
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
                        // Infolists\Components\TextEntry::make('causer.ip')
                        // ->label('IP'),
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
            ])
            ->record($record);
    }
}
