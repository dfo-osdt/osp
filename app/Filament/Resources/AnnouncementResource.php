<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnnouncementResource\Pages;
use App\Models\Announcement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Announcement Content')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title_en')
                            ->label('Title English')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('title_fr')
                            ->label('Title French')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('text_en')
                            ->label('Announcement English')
                            ->autosize()
                            ->required()
                            ->maxLength(500),
                        Forms\Components\Textarea::make('text_fr')
                            ->label('Announcement French')
                            ->autosize()
                            ->required()
                            ->maxLength(500),
                    ]),
                Forms\Components\Section::make('Dates')
                    ->description('All announcements will start at 00:00:00 UTC and end at 23:59:59 UTC of the dates selected!')
                    ->schema([
                        Forms\Components\DateTimePicker::make('start_at')
                            ->label('Start Date')
                            ->Time(false)
                            ->default(fn () => now()->startOfDay())
                            ->required()
                            ->afterOrEqual('yesterday')
                            ->beforeOrEqual('end_at'),
                        Forms\Components\DateTimePicker::make('end_at')
                            ->label('End Date')
                            ->Time(false)
                            ->default(fn () => now()->endOfDay())
                            ->required()
                            ->afterOrEqual('start_at'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title_en')
                    ->label('Title English')
                    ->wrap()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title_fr')
                    ->label('Title French')
                    ->wrap()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created (UTC)')
                    ->datetime('M d, Y H:i'),
                Tables\Columns\TextColumn::make('start_at')
                    ->label('Start (UTC)')
                    ->datetime('M d, Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_at')
                    ->label('End (UTC)')
                    ->datetime('M d, Y H:i')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(function ($record) {
                        $now = Carbon::now();
                        if ($record->start_at > $now) {
                            return 'Upcoming';
                        }
                        if ($record->start_at <= $now && $record->end_at >= $now) {
                            return 'Active';
                        }
                        if ($record->end_at < $now) {
                            return 'Passed';
                        }
                    })
                    ->colors([
                        'warning' => 'Upcoming',
                        'success' => 'Active',
                        'info' => 'Passed',
                    ]),
            ])
            ->defaultSort('start_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['end_at'] = $data['end_at'].'T23:59:59';

                        return $data;
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Info')
                    ->schema([
                        Components\Grid::make(2)
                            ->schema([
                                Components\Group::make([
                                    TextEntry::make('title_en')
                                        ->label('Title English'),
                                    TextEntry::make('created_at')
                                        ->label('Created (UTC)')
                                        ->datetime('M d, Y H:i'),
                                    TextEntry::make('start_at')
                                        ->label('Start (UTC)')
                                        ->datetime('M d, Y H:i'),
                                ]),
                                Components\Group::make([
                                    TextEntry::make('title_fr')
                                        ->label('Title French'),
                                    TextEntry::make('status')
                                        ->badge()
                                        ->getStateUsing(function ($record) {
                                            $now = Carbon::now();
                                            if ($record->start_at > $now) {
                                                return 'Upcoming';
                                            }
                                            if ($record->start_at <= $now && $record->end_at >= $now) {
                                                return 'Active';
                                            }
                                            if ($record->end_at < $now) {
                                                return 'Passed';
                                            }
                                        })
                                        ->colors([
                                            'secondary' => 'Upcoming',
                                            'success' => 'Active',
                                            'danger' => 'Passed',
                                        ]),
                                    TextEntry::make('end_at')
                                        ->label('End (UTC)')
                                        ->datetime('M d, Y H:i'),
                                ])
                                    ->columns(1),
                            ]),
                    ]),
                Components\Section::make('Message')
                    ->schema([
                        Components\TextEntry::make('text_en')
                            ->label('English'),
                        Components\TextEntry::make('text_fr')
                            ->label('French'),
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
            'index' => Pages\ListAnnouncements::route('/'),
            'create' => Pages\CreateAnnouncement::route('/create'),
            //	    'edit' => Pages\EditAnnouncement::route('/{record}/edit'),
        ];
    }
}
