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

class ViewUserLogins extends ManageRelatedRecords
{
    protected static string $resource = UserResource::class;

    protected static string $relationship = 'authentications';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'View Authentications';
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
            ->recordTitleAttribute('authenticatable_id')
            ->columns([
                Tables\Columns\TextColumn::make('authenticatable_id')
                    ->hidden(),
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
