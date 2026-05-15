<?php

namespace App\Filament\Resources\InventarioTiendas\Schemas;


use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class InventarioTiendaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                
            Select::make('producto_id')
                ->relationship('producto', 'nombre')
                ->required(),

            TextInput::make('cantidad_actual')
                ->numeric()
                ->default(0),

            TextInput::make('cantidad_enviada')
                ->numeric()
                ->default(0),

            TextInput::make('cantidad_vendida')
                ->numeric()
                ->default(0),

            TextInput::make('cantidad_devuelta')
                ->numeric()
                ->default(0),

            //TextInput::make('ubicacion')
            //    ->default('Tienda'),
        
            ]);
    }
}
