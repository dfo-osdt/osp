<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
					  ->Filled()
					  ->required(),
		Forms\Components\TextInput::make('last_name')
					  ->disabledOn('edit')
					  ->Filled(),
		Forms\Components\TextInput::make('email')
					  ->disabledOn('edit')
					  ->Filled(),
		Forms\Components\Section::make([
		    Forms\Components\CheckboxList::make('roles')
						 ->relationship(titleAttribute: 'name')
						 ->default(['1'])
						 ->options([
						     '3' => 'Admin',
						     '2' => 'Director',
						 ]),
		])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
					 ->sortable(),
		Tables\Columns\TextColumn::make('last_name')
					 ->sortable(),
		Tables\Columns\TextColumn::make('email'),
		Tables\Columns\IconColumn::make('active')
					 ->boolean()
					 ->sortable(),
		Tables\Columns\TextColumn::make('roles.name')
					 ->badge()
					 ->color(fn (string $state): string => match ($state) {
					     'author' => 'success',
					     'director' => 'warning',
					     'admin' => 'danger',
					 })
					 ->searchable(isIndividual: true),
            ])
            ->filters([
                //
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
