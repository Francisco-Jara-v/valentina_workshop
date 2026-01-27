<?php


namespace App\Filament\Resources\Pedidos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Models\Insumo;

class PedidoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                /*
                |--------------------------------------------------------------------------
                | INFORMACIÓN DEL PEDIDO
                |--------------------------------------------------------------------------
                */
                Section::make('Información del Pedido')
                    ->schema([
                        TextInput::make('cliente_nombre')
                            ->label('Nombre del Cliente')
                            ->required(),

                        DatePicker::make('fecha_pedido')
                            ->required(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                /*
                |--------------------------------------------------------------------------
                | DETALLES DEL PEDIDO
                |--------------------------------------------------------------------------
                */
                Section::make('Detalles del Pedido')
                    ->schema([
                        Repeater::make('detalles')
                            ->relationship('detalles')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {

                                $total = collect($state)->sum(fn ($item) =>
                                    (float) ($item['subtotal'] ?? 0)
                                );

                                $set('total', $total);

                                $valorVenta = $get('valor_venta') ?? 0;
                                $set('ganancia', $valorVenta - $total);
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
                                    ->default(1)
                                    ->reactive()
                                    ->live()
                                    ->minValue(1)
                                    ->maxValue(fn (callable $get) =>
                                        Insumo::find($get('insumo_id'))?->cantidad ?? 0
                                    )
                                    ->hint(fn (callable $get) =>
                                        'Stock disponible: ' .
                                        (Insumo::find($get('insumo_id'))?->cantidad ?? 0)
                                    )
                                    ->validationMessages([
                                        'max' => 'No hay stock suficiente para este insumo',
                                        'min' => 'La cantidad mínima es 1',
                                        'required' => 'La cantidad es obligatoria',
                                    ])
                                    ->afterStateUpdated(fn ($state, $set, $get) =>
                                        $set('subtotal', $state * ($get('precio_unitario') ?? 0))
                                    ),

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
                            ->addAction(function(callable $get, callable $set) {
                                $total = collect($get('detalles'))->sum(fn ($item) =>
                                    (float) ($item['subtotal'] ?? 0)
                                );
                                $set('total', $total);
                                $valorVenta = $get('valor_venta') ?? 0;
                                $set('ganancia', $valorVenta - $total);
                            })
                    ])
                    ->columnSpanFull(),

                /*
                |--------------------------------------------------------------------------
                | INFORMACIÓN ADICIONAL
                |--------------------------------------------------------------------------
                */
                Section::make('Información Adicional')
                    ->schema([
                        Textarea::make('descripcion')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                /*
                |--------------------------------------------------------------------------
                | TOTALES
                |--------------------------------------------------------------------------
                */
                Section::make('Totales del Pedido')
                    ->schema([
                        TextInput::make('valor_venta')
                            ->label('Valor de Venta')
                            ->numeric()
                            ->prefix('$')
                            ->required()
                            ->live(debounce: 500)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $total = $get('total') ?? 0;
                                $set('ganancia', $state - $total);
                            }),

                        TextInput::make('total')
                            ->label('Total de Insumos')
                            ->numeric()
                            ->prefix('$')
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('ganancia')
                            ->numeric()
                            ->prefix('$')
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('estado')
                            ->default('PENDIENTE')
                            ->disabled()
                            ->required(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}