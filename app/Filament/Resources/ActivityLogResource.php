<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use App\Filament\Resources\ActivityLogResource\RelationManagers;
use App\Models\ActivityLog;
use Aws\Script\Composer\Composer;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

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
                ->label('Date'),
                Tables\Columns\TextColumn::make('event'),
                // Tables\Columns\TextColumn::make('description')
                // ->limit(50),
                Tables\Columns\TextColumn::make('causer.email'),
                Tables\Columns\TextColumn::make('subject.email'),
                Tables\Columns\TextColumn::make('log_name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //,
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
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
        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
            //'view' => Pages\ViewActivityLog::route('/{record}'),
        ];
    }
}
