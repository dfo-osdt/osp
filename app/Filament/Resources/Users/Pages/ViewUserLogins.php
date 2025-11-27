<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\Users\UserResource;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ViewUserLogins extends ManageRelatedRecords
{
    protected static string $resource = UserResource::class;

    protected static string $relationship = 'authentications';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'View Authentications';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Back')
                ->url(fn (): string => UserResource::getUrl('index'))
                ->icon('heroicon-o-arrow-small-left'),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('authenticatable_id')
            ->columns([
                TextColumn::make('authenticatable_id')
                    ->hidden(),
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
                    ->query(fn(Builder $query, array $data): Builder => $query
                        ->when(
                            $data['logins_from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('login_at', '>=', $date),
                        )
                        ->when(
                            $data['logins_until'],
                            fn (Builder $query, $date): Builder => $query->whereDate('login_at', '<=', $date),
                        ))
                    ->columns(2),
                TernaryFilter::make('login_successful'),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->recordActions([
                ViewAction::make(),
            ]);
    }

    public function infolist(Schema $schema): Schema
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
}
