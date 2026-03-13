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
                
                TextColumn::make('fecha_entrega')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('fecha_pedido')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('estado')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'PENDIENTE' => 'warning',
                        'ENTREGADO' => 'success',
                        'LISTO' => 'success',
                        default => 'gray',
                    })
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('cliente_nombre')
                    ->label('Cliente')
                    ->searchable(),
                TextColumn::make('descripcion')
                    ->label('Descripción')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: false),
                
                BadgeColumn::make('estado_pago')
                    ->label('Estado de Pago')
                    ->sortable()
                    ->colors([
                        'danger' => 'PAGO PENDIENTE',
                        'warning' => 'ABONADO',
                        'success' => 'PAGADO',
                    ])
                    ->icons([
                        'heroicon-o-clock' => 'PAGO PENDIENTE',
                        'heroicon-o-banknotes' => 'ABONADO',
                        'heroicon-o-check-circle' => 'PAGADO',
                    ])
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('valor_venta')
                    ->label('Valor de Venta')
                    ->numeric()
                    ->sortable()
                    ->money('CLP')
                    ->badge()
                    ->color('warning')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('total')
                    ->label('Costo Total')
                    ->numeric()
                    ->sortable()
                    ->money('CLP')
                    ->badge()
                    ->color('danger')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('ganancia')
                    ->numeric()
                    ->sortable()
                    ->money('CLP')
                    ->badge()
                    ->color('success')
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('monto_pagado')
                    ->label('Monto Pagado')
                    ->badge()
                    ->money('CLP', true)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Action::make('Entregado')
                    ->color('success')
                    ->action(function ($record) {
                        $ahora = now('America/Santiago');
                        $record->update([
                            'estado' => 'ENTREGADO',
                            'fecha_entrega' => $ahora,]);
                    })
                    ->visible(fn ($record) => $record->estado !== 'ENTREGADO'),
                Action::make('Listo')
                    ->color('success')
                    ->action(function ($record) {
                        $record->estado = 'LISTO';
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
            ->defaultPaginationPageOption(50)
            ->paginationPageOptions([25, 50, 100]);
            /*->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])*/;
    }
}
