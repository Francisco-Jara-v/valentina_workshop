<?php

namespace App\Filament\Resources\Pedidos\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PedidosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                
                TextColumn::make('fecha_pedido')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('cliente_nombre')
                    ->label('Cliente')
                    ->searchable(),
                TextColumn::make('descripcion')
                    ->label('Descripción')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('valor_venta')
                    ->label('Valor de Venta')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('total')
                    ->label('Costo Total')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('ganancia')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('estado')
                    ->searchable()
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'PENDIENTE' => 'warning',
                        'ENTREGADO' => 'success',
                        default => 'gray',
                    })
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('Entregar')
                    ->action(function ($record) {
                        $record->estado = 'ENTREGADO';
                        $record->save();
                    })
                    ->visible(fn ($record) => $record->estado === 'PENDIENTE'),
            ])
            /*->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])*/;
    }
}
