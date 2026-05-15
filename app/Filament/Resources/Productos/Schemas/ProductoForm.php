<?php

namespace App\Filament\Resources\Productos\Schemas;

use App\Models\Insumo;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                /*
                |--------------------------------------------------------------------------
                | INFORMACIÓN DEL PRODUCTO
                |--------------------------------------------------------------------------
                */
                Section::make('Información del Producto')
                    ->schema([

                        TextInput::make('nombre')
                            ->required(),

                        Textarea::make('descripcion')
                            ->columnSpanFull(),



                        TextInput::make('stock_minimo')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        //TextInput::make('stock_taller')
                        //    ->numeric()
                        //    ->default(0)
                        //    ->required(),

                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                /*
                |--------------------------------------------------------------------------
                | INSUMOS DEL PRODUCTO
                |--------------------------------------------------------------------------
                */
                Section::make('Insumos del Producto')
                    ->schema([

                        Repeater::make('productoInsumos')
                            ->relationship('productoInsumos')
                            ->reactive()
                            ->live()

                            ->afterStateUpdated(function ($state, callable $set) {

                                $total = 0;

                                foreach ($state ?? [] as $item) {

                                    if (
                                        empty($item['insumo_id']) ||
                                        empty($item['cantidad'])
                                    ) {
                                        continue;
                                    }

                                    $insumo = Insumo::find($item['insumo_id']);

                                    if (! $insumo) {
                                        continue;
                                    }

                                    $total +=
                                        $insumo->precio_unitario *
                                        $item['cantidad'];
                                }

                                $set('precio_costo', $total);
                            })

                            ->schema([

                                Select::make('insumo_id')
                                    ->label('Insumo')
                                    ->options(Insumo::pluck('nombre', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->live()

                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {

                                        $precio = Insumo::find($state)?->precio_unitario ?? 0;

                                        $cantidad = $get('cantidad') ?? 1;

                                        $subtotal = $precio * $cantidad;

                                        $set('precio_unitario', $precio);

                                        $set('subtotal', $subtotal);
                                    }),

                                TextInput::make('cantidad')
                                    ->numeric()
                                    ->minValue(1)
                                    ->required()
                                    ->live()

                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {

                                        $precio = $get('precio_unitario') ?? 0;

                                        $set('subtotal', $precio * $state);
                                    }),

                                TextInput::make('precio_unitario')
                                    ->numeric()
                                    ->prefix('$')
                                    ->disabled()
                                    ->dehydrated(),

                                TextInput::make('subtotal')
                                    ->numeric()
                                    ->prefix('$')
                                    ->disabled()
                                    ->dehydrated(),

                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->collapsible(),
                Section::make('Valores')
                    ->schema([
                        TextInput::make('valor_venta')
                            ->label('Precio Venta')
                            ->numeric()
                            ->prefix('$')
                            ->required(),

                        TextInput::make('precio_costo')
                            ->numeric()
                            ->prefix('$')
                            ->disabled()
                            ->dehydrated(),
                    ])

                    ])
                    ->columnSpanFull(),
            ]);
    }
}