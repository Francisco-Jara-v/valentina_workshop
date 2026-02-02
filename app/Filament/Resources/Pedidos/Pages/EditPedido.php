<?php

namespace App\Filament\Resources\Pedidos\Pages;

use App\Filament\Resources\Pedidos\PedidoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Models\Insumo;

class EditPedido extends EditRecord
{
    protected static string $resource = PedidoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->before(function () {
                    // DEVOLVER STOCK AL BORRAR
                    foreach ($this->record->detalles as $detalle) {
                        $insumo = Insumo::find($detalle->insumo_id);
                        if ($insumo) {
                            $insumo->increment('cantidad', $detalle->cantidad);
                        }
                    }
                }),
        ];
    }

    protected function beforeSave(): void
    {
        // 🔁 DEVOLVER STOCK ORIGINAL
        foreach ($this->record->detalles as $detalle) {
            $insumo = Insumo::find($detalle->insumo_id);

            if ($insumo) {
                $insumo->increment('cantidad', $detalle->cantidad);
            }
        }
    }

    protected function afterSave(): void
    {
        // 🔻 DESCONTAR NUEVO STOCK
        foreach ($this->record->detalles as $detalle) {
            $insumo = Insumo::find($detalle->insumo_id);

            if ($insumo) {
                $insumo->decrement('cantidad', $detalle->cantidad);
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
