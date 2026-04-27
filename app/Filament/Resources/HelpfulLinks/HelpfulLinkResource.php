<?php

declare(strict_types=1);

namespace App\Filament\Resources\HelpfulLinks;

use App\Filament\Resources\HelpfulLinks\Pages\CreateHelpfulLink;
use App\Filament\Resources\HelpfulLinks\Pages\EditHelpfulLink;
use App\Filament\Resources\HelpfulLinks\Pages\ListHelpfulLinks;
use App\Filament\Resources\HelpfulLinks\Schemas\HelpfulLinkForm;
use App\Filament\Resources\HelpfulLinks\Tables\HelpfulLinksTable;
use App\Models\HelpfulLink;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class HelpfulLinkResource extends Resource
{
    protected static ?string $model = HelpfulLink::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title_en';

    public static function form(Schema $schema): Schema
    {
        return HelpfulLinkForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HelpfulLinksTable::configure($table);
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
            'index' => ListHelpfulLinks::route('/'),
            'create' => CreateHelpfulLink::route('/create'),
            'edit' => EditHelpfulLink::route('/{record}/edit'),
        ];
    }
}
