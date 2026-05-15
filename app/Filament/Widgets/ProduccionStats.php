<?php

namespace App\Filament\Widgets;

use App\Models\Producto;
use App\Models\Setting;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use Illuminate\Support\Facades\DB;

class ProduccionStats extends StatsOverviewWidget
{
    public static function canView(): bool
    {
        return
            Setting::getSetting(
                'dashboard'
            )['widgets']['produccion']
            ?? true;
    }
    protected ?string $heading = 'Producción';
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 'full';
    protected function getColumns(): int
    {
        return 5;
    }

    
    protected function getStats(): array
    {
        /*
        |--------------------------------------------------------------------------
        | COSTO FABRICACIÓN
        |--------------------------------------------------------------------------
        */

        $costoFabricado = Producto::query()
            ->with('inventarioTienda')
            ->get()
            ->sum(function ($producto) {

                $cantidadTienda =
                    $producto->inventarioTienda->cantidad_actual ?? 0;

                $cantidadVendida =
                    $producto->inventarioTienda->cantidad_vendida ?? 0;

                return
                    $producto->precio_costo *
                    (
                        $producto->stock_taller +
                        $cantidadTienda +
                        $cantidadVendida
                    );
            });

        /*
        |--------------------------------------------------------------------------
        | GANANCIA PRODUCTOS
        |--------------------------------------------------------------------------
        */

        $gananciaProductos = Producto::query()
            ->with('inventarioTienda')
            ->get()
            ->sum(function ($producto) {

                return
                    (
                        $producto->valor_venta -
                        $producto->precio_costo
                    )
                    *
                    ($producto->inventarioTienda->cantidad_vendida ?? 0);
            });

        /*
        |--------------------------------------------------------------------------
        | CANTIDADES
        |--------------------------------------------------------------------------
        */

        $cantidadTaller = Producto::sum('stock_taller');

        $cantidadTienda = Producto::query()
            ->with('inventarioTienda')
            ->get()
            ->sum(fn ($producto) =>
                $producto->inventarioTienda->cantidad_actual ?? 0
            );

        $cantidadVendida = Producto::query()
            ->with('inventarioTienda')
            ->get()
            ->sum(fn ($producto) =>
                $producto->inventarioTienda->cantidad_vendida ?? 0
            );

        return [

            Stat::make(
                'Costo Productos Fabricados',
                '$ ' . number_format($costoFabricado, 0, ',', '.')
            )
                ->description('Costo total histórico')
                ->descriptionIcon('heroicon-o-wrench-screwdriver')
                ->color('warning'),

            Stat::make(
                'Ganancias Productos Vendidos',
                '$ ' . number_format($gananciaProductos, 0, ',', '.')
            )
                ->description('Ganancia estimada')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make(
                'Productos en Taller',
                number_format($cantidadTaller, 0, ',', '.')
            )
                ->description('Stock taller')
                ->descriptionIcon('heroicon-o-cube')
                ->color('info'),

            Stat::make(
                'Productos en Tienda',
                number_format($cantidadTienda, 0, ',', '.')
            )
                ->description('Stock tienda')
                ->descriptionIcon('heroicon-o-building-storefront')
                ->color('primary'),

            Stat::make(
                'Cantidad Vendida',
                number_format($cantidadVendida, 0, ',', '.')
            )
                ->description('Productos vendidos en tienda')
                ->descriptionIcon('heroicon-o-shopping-bag')
                ->color('success'),
        ];
    }
}