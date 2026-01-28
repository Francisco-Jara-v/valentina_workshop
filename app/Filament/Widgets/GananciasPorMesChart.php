<?php

namespace App\Filament\Widgets;

use App\Models\Insumo;
use App\Models\Pedido;
use Filament\Widgets\ChartWidget;

class GananciasPorMesChart extends ChartWidget
{

    protected static ?int $sort = 1;
    protected ?string $heading = 'Ganancias Por Mes Chart';

    protected ?string $maxHeight = '500px';

    protected int|string|array $columnSpan = 4;

    public ?string $month = null;

    protected function getFilters(): ?array
    {
        return collect(range(1, 12))
            ->mapWithKeys(fn (int $month) => [
                (string)$month => date('F', mktime(0, 0, 0, $month, 10)),
            ])
            ->toArray();
    }

    protected function getData(): array
    {
        $month = $this->filter ?? now()->month;

        $data = Pedido::query()
            ->whereMonth('fecha_pedido', $month)
            ->selectRaw('SUM(ganancia) as total_ganancia, DAY(fecha_pedido) as dia')
            ->groupBy('dia')
            ->orderBy('dia')
            ->pluck('total_ganancia', 'dia');

        $invertido = Insumo::query()
            ->whereMonth('created_at', $month)
            ->selectRaw('SUM(precio_unitario)*SUM(cantidad) as total_invertido, DAY(created_at) as dia')
            ->groupBy('dia')
            ->orderBy('dia')
            ->pluck('total_invertido', 'dia');

        return [
            'datasets' => [
                [
                    'label' => 'Inversión',
                    'data' => $invertido->values(),
                    'borderColor' => '#fbbf24',
                    'backgroundColor' => 'rgba(251, 191, 36, 0.25)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Ganancia',
                    'data' => $data->values(),
                    'borderColor' => '#ea6fa6',
                    'backgroundColor' => 'rgba(234, 111, 166, 0.25)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],

            'labels' => $data->keys(),
        ];
    }



    protected function getType(): string
    {
        return 'line';
    }
}
