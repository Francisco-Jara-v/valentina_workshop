<?php

namespace App\Filament\Resources\Productos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ShowAction;
use Filament\Actions\Action;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use App\Actions\FabricarProductoAction;
use App\Actions\TrasladarTiendaAction;
use Filament\Notifications\Notification;
use App\Actions\RegistrarVentaAction;
use App\Actions\RegistrarDevolucionAction;
use Filament\Actions\ActionGroup;

class ProductosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->label('Producto')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('stock_taller')
                    ->label('Taller')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('inventarioTienda.cantidad_actual')
                    ->label('Tienda')
                    ->badge()
                    ->color('warning')
                    ->default(0)
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('inventarioTienda.cantidad_vendida')
                    ->label('Vendidos')
                    ->badge()
                    ->color('success')
                    ->default(0)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('inventarioTienda.cantidad_devuelta')
                    ->label('Devueltos')
                    ->badge()
                    ->color('danger')
                    ->default(0)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('precio_costo')
                    ->money('CLP')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('valor_venta')
                    ->money('CLP')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('ganancia')
                    ->label('Ganancia')
                    ->money('CLP')
                    ->state(fn ($record) =>
                        $record->valor_venta - $record->precio_costo
                        ),

            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('fabricar')
                        ->label('Fabricar')
                        ->icon('heroicon-o-wrench-screwdriver')

                        ->form([

                            TextInput::make('cantidad')
                                ->numeric()
                                ->required()
                                ->minValue(1),

                        ])
                        ->action(function (array $data, $record)
                        {
                            try {
                                FabricarProductoAction::execute(
                                    $record,
                                    $data['cantidad']
                                );
                                Notification::make()
                                    ->title('Producto fabricado correctamente')
                                    ->success()
                                    ->send();
                            } catch (Exception $e) {
                                Notification::make()
                                    ->title($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }),
                    Action::make('trasladar')
                        ->label('Enviar a Tienda')
                        ->icon('heroicon-o-building-storefront')

                        ->form([

                            TextInput::make('cantidad')
                                ->numeric()
                                ->required()
                                ->minValue(1),

                        ])

                        ->action(function (array $data, $record) {

                            try {

                                TrasladarTiendaAction::execute(
                                    $record,
                                    $data['cantidad']
                                );

                                Notification::make()
                                    ->title('Productos enviados a tienda')
                                    ->success()
                                    ->send();

                            } catch (\Exception $e) {

                                Notification::make()
                                    ->title($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        })
                        ->color('warning'),
                    Action::make('registrarVenta')
                        ->label('Registrar venta')
                        ->icon('heroicon-o-shopping-bag')

                        ->form([

                            TextInput::make('cantidad')
                                ->numeric()
                                ->required()
                                ->minValue(1),

                        ])

                        ->action(function (array $data, $record) {

                            try {

                                RegistrarVentaAction::execute(
                                    $record,
                                    $data['cantidad']
                                );

                                Notification::make()
                                    ->title('Venta registrada correctamente')
                                    ->success()
                                    ->send();

                            } catch (\Exception $e) {

                                Notification::make()
                                    ->title($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        })
                        ->color('success'),
                    Action::make('registrarDevolucion')
                        ->label('Registrar devolución')
                        ->icon('heroicon-o-arrow-uturn-left')

                        ->form([

                            TextInput::make('cantidad')
                                ->numeric()
                                ->required()
                                ->minValue(1),

                        ])

                        ->action(function (array $data, $record) {

                            try {

                                RegistrarDevolucionAction::execute(
                                    $record,
                                    $data['cantidad']
                                );

                                Notification::make()
                                    ->title('Devolución registrada correctamente')
                                    ->success()
                                    ->send();

                            } catch (\Exception $e) {

                                Notification::make()
                                    ->title($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        })
                        ->color('danger'),
                    EditAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
