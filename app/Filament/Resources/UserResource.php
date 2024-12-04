<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Rules\AuthorizedEmailDomain;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')
                    ->disabledOn('edit')
                    ->filled()
                    ->required(),
                Forms\Components\TextInput::make('last_name')
                    ->disabledOn('edit')
                    ->filled(),

                Forms\Components\Section::make('User Information')
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->filled()
                            ->rules(['bail', 'required', 'string', 'email', new AuthorizedEmailDomain]),
                        Forms\Components\CheckboxList::make('roles')
                            ->relationship(titleAttribute: 'name')
                            ->label('Roles'),
                    ]),

                Forms\Components\Section::make('Available Actions')
                    ->description('Actions are instantaneous and may disappear once applied!')
                    ->schema([
                        Forms\Components\Toggle::make('active')
                            ->label('Activate User')
                            ->dehydrated(false)
                            ->onColor('success'),
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('verify_email')
                                ->hidden(fn ($record) => $record && $record->email_verified_at)
                                ->action('setVerifiedEmail'),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Email Verified')
                    ->default('heroicon-o-x-circle')
                    ->icon(fn ($state) => $state instanceof \DateTime ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->color(fn ($state) => $state instanceof \DateTime ? 'success' : 'danger')
                    ->sortable(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'author' => 'success',
                        'director' => 'warning',
                        'admin' => 'danger',
                        'default' => 'info',
                    })
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('email_verified_at')
                    ->label('Verified')
                    ->nullable()
                    ->placeholder('All'),
                Tables\Filters\SelectFilter::make('active')
                    ->options([
                        true => 'Active',
                        false => 'Inactive',
                    ]),
                Tables\Filters\Filter::make('no_roles')
                    ->label('No Roles')
                    ->query(fn ($query) => $query->whereDoesntHave('roles')),
                Tables\Filters\SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->label('Role'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Placeholder
                ]),
            ])
            ->defaultSort('first_name');
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
