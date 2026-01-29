<?php

namespace App\Filament\Resources\Pedidos\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\BadgeColumn;

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
                    ->money('CLP')
                    ->badge()
                    ->color('warning')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('total')
                    ->label('Costo Total')
                    ->numeric()
                    ->sortable()
                    ->money('CLP')
                    ->badge()
                    ->color('danger')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('ganancia')
                    ->numeric()
                    ->sortable()
                    ->money('CLP')
                    ->badge()
                    ->color('success')
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
                TextColumn::make('monto_pagado')
                    ->label('Monto Pagado')
                    ->badge()
                    ->money('CLP', true)
                    ->sortable(),
    
                BadgeColumn::make('estado_pago')
                    ->label('Estado de Pago')
                    ->colors([
                        'danger' => 'PAGO PENDIENTE',
                        'warning' => 'ABONADO',
                        'success' => 'PAGADO',
                    ])
                    ->icons([
                        'heroicon-o-clock' => 'PAGO PENDIENTE',
                        'heroicon-o-banknotes' => 'ABONADO',
                        'heroicon-o-check-circle' => 'PAGADO',
                    ]),
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
                Action::make('Marcar Como Entregado')
                    ->color('success')
                    ->action(function ($record) {
                        $record->estado = 'ENTREGADO';
                        $record->save();
                    })
                    ->visible(fn ($record) => $record->estado === 'PENDIENTE'),
                Action::make('pagar')
                    ->label('Pagar')
                    ->icon('heroicon-o-currency-dollar')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'estado_pago'  => 'PAGADO',
                            'monto_pagado' => $record->valor_venta,
                        ]);
                    })
                    ->visible(fn ($record) => $record->estado_pago !== 'PAGADO'),
            ])
            /*->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])*/;
    }
}
