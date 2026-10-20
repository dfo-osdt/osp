<?php

declare(strict_types=1);

namespace App\Filament\Resources\Sectors\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SectorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name_en')
                    ->label('Name (EN)')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name_fr')
                    ->label('Name (FR)')
                    ->sortable()
                    ->searchable(),
            ])
            ->defaultSort('name_en')
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
