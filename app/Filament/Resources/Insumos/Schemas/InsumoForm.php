<?php

namespace App\Filament\Resources\Insumos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class InsumoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required(),
                Textarea::make('descripcion')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('cantidad')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('unidad_medida')
                    ->required(),
                TextInput::make('stock_minimo')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('precio_unitario')
                    ->required()
                    ->numeric(),
            ]);
    }
}
