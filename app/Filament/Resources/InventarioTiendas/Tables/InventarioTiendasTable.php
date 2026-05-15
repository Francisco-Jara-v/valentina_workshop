<?php

namespace App\Filament\Resources\InventarioTiendas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class InventarioTiendasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('producto.nombre')
                ->label('Nombre Producto')
                ->sortable()
                ->searchable(),
                TextColumn::make('cantidad_actual')
                ->badge(),
                TextColumn::make('cantidad_enviada')
                ->badge(),
                TextColumn::make('cantidad_vendida')
                ->badge(),
                TextColumn::make('cantidad_devuelta')
                ->badge(),

            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
