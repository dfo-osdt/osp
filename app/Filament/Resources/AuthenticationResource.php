<?php

namespace App\Filament\Resources;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\ViewAction;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\KeyValueEntry;
use App\Filament\Resources\AuthenticationResource\Pages\ListAuthentications;
use App\Filament\Resources\AuthenticationResource\Pages\ViewAuthentication;
use App\Filament\Resources\AuthenticationResource\Pages;
use Filament\Forms;
use Filament\Infolists;
use Filament\Infolists\Components;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog;

class AuthenticationResource extends Resource
{
    protected static ?string $model = AuthenticationLog::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Audit Logs';

    protected static ?string $navigationLabel = 'Authentications';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('login_at')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('login_successful')
                    ->label('Login Successful')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('authenticatable.email')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('ip_address')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('logout_at')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Filter::make('login_at')
                    ->schema([
                        DatePicker::make('logins_from'),
                        DatePicker::make('logins_until')
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
                TernaryFilter::make('login_successful'),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->recordActions([
                ViewAction::make(),
            ]);
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
            'index' => ListAuthentications::route('/'),
            'view' => ViewAuthentication::route('/{record}'),
        ];
    }
}
