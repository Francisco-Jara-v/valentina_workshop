<?php

namespace App\Filament\Widgets;

use App\Models\Insumo;
use App\Models\Producto;
use App\Models\Pedido;
use App\Models\Setting;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use Illuminate\Support\Facades\DB;

class ResumenFinanciero extends StatsOverviewWidget
{
    public static function canView(): bool
    {
        return
            Setting::getSetting(
                'dashboard'
            )['widgets']['resumen_financiero']
            ?? true;
    }

    protected ?string $heading = 'Histórico';

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 1;

    protected function getColumns(): int
    {
        return 2;
    }

    protected function getStats(): array
    {
        /*
        |--------------------------------------------------------------------------
        | INSUMOS
        |--------------------------------------------------------------------------
        */

        $totalInsumos = Insumo::sum(
            DB::raw('precio_unitario * cantidad')
        );

        /*
        |--------------------------------------------------------------------------
        | HISTÓRICO PRODUCTOS
        |--------------------------------------------------------------------------
        */

        $historicoProductos = Producto::query()
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
        | HISTÓRICO TOTAL
        |--------------------------------------------------------------------------
        */

        $historicoTotal =
            $totalInsumos +
            $historicoProductos;

        /*
        |--------------------------------------------------------------------------
        | GANANCIA PEDIDOS
        |--------------------------------------------------------------------------
        */

        $gananciaPedidos =
            Pedido::sum('ganancia');

        /*
        |--------------------------------------------------------------------------
        | GANANCIA TIENDA
        |--------------------------------------------------------------------------
        */

        $gananciaTienda = Producto::query()
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
        | INGRESOS NETOS
        |--------------------------------------------------------------------------
        */

        $ingresosNetos =
            $gananciaPedidos +
            $gananciaTienda;

        return [

            Stat::make(
                'Histórico Invertido',
                '$ ' . number_format($historicoTotal, 0, ',', '.')
            )
                ->description('Total histórico fabricado')
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color('danger'),

            Stat::make(
                'Ingresos Netos',
                '$ ' . number_format($ingresosNetos, 0, ',', '.')
            )
                ->description('Ganancias totales')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success'),
        ];
    }
}