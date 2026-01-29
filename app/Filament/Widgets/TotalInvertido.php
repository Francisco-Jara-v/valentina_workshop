<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalInvertido extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 4;

    protected function getStats(): array
    {
        $gastoPedido = \App\Models\Pedido::sum('total');

        $totalInvertido = \App\Models\Pedido::sum('total') + \App\Models\Insumo::sum(DB::raw('precio_unitario * cantidad'));
        $totalGanancias = \App\Models\Pedido::sum('ganancia');
        return [
            Stat::make('Total Invertido', '$ ' . number_format($totalInvertido, 0, ',', '.'))
                ->description('Total historico Invertido')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('warning')
                ,
            
            Stat::make('Ganancia Total', '$ ' . number_format($totalGanancias, 0, ',', '.'))
                ->description('Ingresos netos')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Invertido por Pedido', '$ ' . number_format($gastoPedido, 0, ',', '.'))
                ->description('Costo total de todos los pedidos.')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('danger'),
        ];
        
    }
}
