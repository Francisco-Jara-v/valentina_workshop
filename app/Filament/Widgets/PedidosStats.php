<?php

namespace App\Filament\Widgets;

use App\Models\Pedido;
use App\Models\Setting;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PedidosStats extends StatsOverviewWidget
{
    public static function canView(): bool
    {
        return
            Setting::getSetting(
                'dashboard'
            )['widgets']['pedidos']
            ?? true;
    }
    protected ?string $heading = 'Pedidos';
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 1;
    protected function getColumns(): int
    {
        return 2;
    }

    protected function getStats(): array
    {
        $costoPedidos = Pedido::sum('total');

        $gananciaPedidos = Pedido::sum('ganancia');

        return [

            Stat::make(
                'Costo Total Pedidos',
                '$ ' . number_format($costoPedidos, 0, ',', '.')
            )
                ->description('Costo acumulado pedidos')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('warning'),

            Stat::make(
                'Ganancias Pedidos',
                '$ ' . number_format($gananciaPedidos, 0, ',', '.')
            )
                ->description('Ganancia pedidos personalizados')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color('success'),
        ];
    }
}