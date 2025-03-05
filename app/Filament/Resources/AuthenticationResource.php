<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuthenticationResource\Pages;
use Filament\Forms;
use Filament\Infolists;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog;

class AuthenticationResource extends Resource
{
    protected static ?string $model = AuthenticationLog::class;

    protected static ?string $navigationGroup = 'Audit Logs';

    protected static ?string $navigationLabel = 'Authentications';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('login_at')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('login_successful')
                    ->label('Login Successful')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('authenticatable.email')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ip_address')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('logout_at')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('login_at')
                    ->form([
                        Forms\Components\DatePicker::make('logins_from'),
                        Forms\Components\DatePicker::make('logins_until')
                            ->default(now()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['logins_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('login_at', '>=', $date),
                            )
                            ->when(
                                $data['logins_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('login_at', '<=', $date),
                            );
                    })
                    ->columns(2),
                Tables\Filters\TernaryFilter::make('login_successful'),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
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

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuthentications::route('/'),
            'view' => Pages\ViewAuthentication::route('/{record}'),
        ];
    }
}
