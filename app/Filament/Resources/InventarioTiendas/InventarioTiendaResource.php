<?php

namespace App\Filament\Resources\InventarioTiendas;

use App\Filament\Resources\InventarioTiendas\Pages\CreateInventarioTienda;
use App\Filament\Resources\InventarioTiendas\Pages\EditInventarioTienda;
use App\Filament\Resources\InventarioTiendas\Pages\ListInventarioTiendas;
use App\Filament\Resources\InventarioTiendas\Schemas\InventarioTiendaForm;
use App\Filament\Resources\InventarioTiendas\Tables\InventarioTiendasTable;
use App\Models\InventarioTienda;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InventarioTiendaResource extends Resource
{
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
    protected static ?string $model = InventarioTienda::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'InventarioTienda';

    public static function form(Schema $schema): Schema
    {
        return InventarioTiendaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InventarioTiendasTable::configure($table);
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
            'index' => ListInventarioTiendas::route('/'),
            'create' => CreateInventarioTienda::route('/create'),
            'edit' => EditInventarioTienda::route('/{record}/edit'),
        ];
    }
}
