<?php

namespace App\Filament\Widgets;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Pedido;

class PedidosPendientes extends TableWidget
{
    protected static ?int $sort = 3;
    protected static ?string $heading = 'Pedidos Pendientes de Entrega';
    protected int|string|array $columnSpan =  4;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Pedido::query()
                ->where('estado', 'PENDIENTE')
                ->orderBy('fecha_pedido','asc')
            )
            ->columns([
                TextColumn::make('fecha_pedido')
                    ->date()
                    ->label('Fecha del Pedido')
                    ->sortable(),
                TextColumn::make('cliente_nombre')
                    ->label('Cliente')
                    ->searchable(),
                TextColumn::make('descripcion')
                    ->label('Descripción')
                    ->limit(50),
                TextColumn::make('valor_venta')
                    ->label('Valor de Venta')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total')
                    ->label('Costo Total')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ganancia')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('estado')
                    ->searchable()
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'PENDIENTE' => 'warning',
                        'ENTREGADO' => 'success',
                        'LISTO' => 'success',
                        default => 'gray',
                    }),

                    
            ])
            ->actions([
                
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                Action::make('Entregar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn (Pedido $record) => $record->update([
                        'estado' => 'ENTREGADO',
                    ]))
                    ->label('Entregado')
                    ->visible(fn ($record) => $record->estado !== 'ENTREGADO'),
                Action::make('Listo')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn (Pedido $record) => $record->update([
                        'estado' => 'LISTO',
                    ]))
                    ->label('Pedido Listo')
                    ->visible(fn ($record) => $record->estado !== 'ENTREGADO'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
