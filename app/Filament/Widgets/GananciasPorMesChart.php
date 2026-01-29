<?php

namespace App\Filament\Widgets;

use App\Models\Insumo;
use App\Models\Pedido;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class GananciasPorMesChart extends ChartWidget
{

    protected static ?int $sort = 1;
    protected ?string $heading = 'Ganancias Por Mes Chart';

    protected ?string $maxHeight = '500px';

    protected int|string|array $columnSpan = 4;

    public ?string $month = null;


protected function getData(): array
{
    $months = collect(range(1, now()->month))
        ->map(fn ($month) => now()->startOfYear()->addMonths($month - 1));
        //->reverse()
        //->values();

    $labels = [];
    $invertidoPedido = [];
    $ganancias = [];
    $totalInvertido = [];

    foreach ($months as $date) {
        $labels[] = $date->format('Y-m');

        $pedidoTotal = \App\Models\Pedido::whereDate('created_at', '<=', $date->endOfMonth())
            ->sum('total');

        $gananciaTotal = \App\Models\Pedido::whereDate('created_at', '<=', $date->endOfMonth())
            ->sum('ganancia');

        $stockTotal = \App\Models\Insumo::sum(DB::raw('precio_unitario * cantidad'));

        $invertidoPedido[] = $pedidoTotal;
        $ganancias[] = $gananciaTotal;
        $totalInvertido[] = $pedidoTotal + $stockTotal;
    }

    return [
        'labels' => $labels,
        'datasets' => [
            [
                'label' => 'Total Invertido',
                'data' => $totalInvertido,
                'borderColor' => '#F59E0B',
                'backgroundColor' => 'rgba(245,158,11,0.2)',
                'tension' => 0.3,
            ],
            [
                'label' => 'Ganancia Total',
                'data' => $ganancias,
                'borderColor' => '#10B981',
                'backgroundColor' => 'rgba(16,185,129,0.2)',
                'tension' => 0.3,
            ],
            [
                'label' => 'Total Invertido por Pedido',
                'data' => $invertidoPedido,
                'borderColor' => '#EF4444',
                'backgroundColor' => 'rgba(239,68,68,0.2)',
                'tension' => 0.3,
            ],
        ],
    ];
}



    protected function getType(): string
    {
        return 'line';
    }
}
