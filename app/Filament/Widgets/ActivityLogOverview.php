<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ActivityLogResource;
use Filament\Infolists;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ActivityLogOverview extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(ActivityLogResource::getEloquentQuery())
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date'),
                Tables\Columns\TextColumn::make('event'),
                Tables\Columns\TextColumn::make('causer.email'),
                Tables\Columns\TextColumn::make('subject.email'),
                Tables\Columns\TextColumn::make('log_name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading('Activity Log Details')
                    ->modalContent(fn ($record) => $this->infolist($record)),
            ]);
    }

    public static function infolist($record): Infolist
    {
        return Infolists\Infolist::make()
            ->schema([
                Components\Section::make()
                    ->schema([
                        Infolists\Components\TextEntry::make('id'),
                        Infolists\Components\TextEntry::make('created_at'),
                        Infolists\Components\TextEntry::make('event'),
                        Infolists\Components\TextEntry::make('log_name'),
                        Infolists\Components\TextEntry::make('causer.email'),
                        Infolists\Components\TextEntry::make('subject.email'),
                    ])
                    ->columns(2),
                Components\Section::make()
                    ->schema([
                        Infolists\Components\TextEntry::make('description'),
                    ]),
                Components\Section::make()
                    ->schema([
                        Infolists\Components\KeyValueEntry::make('properties'),
                    ]),
            ])
            ->record($record);
    }
}
