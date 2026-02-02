<?php

namespace App\Filament\Resources\Notas;

use App\Filament\Resources\Notas\Pages\CreateNota;
use App\Filament\Resources\Notas\Pages\EditNota;
use App\Filament\Resources\Notas\Pages\ListNotas;
use App\Filament\Resources\Notas\Schemas\NotaForm;
use App\Filament\Resources\Notas\Tables\NotasTable;
use App\Models\Nota;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;


class NotaResource extends Resource
{
    protected static ?string $model = Nota::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static ?string $navigationLabel = 'Notas';

    protected static string | UnitEnum|null $navigationGroup = 'Gestión de Contenido';

    protected static ?string $recordTitleAttribute = 'Nota';

    public static function form(Schema $schema): Schema
    {
        return NotaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NotasTable::configure($table);
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
            'index' => ListNotas::route('/'),
            'create' => CreateNota::route('/create'),
            'edit' => EditNota::route('/{record}/edit'),
            
        ];
    }
}
