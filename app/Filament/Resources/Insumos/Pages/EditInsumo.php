<?php

namespace App\Filament\Resources\Insumos\Pages;

use App\Filament\Resources\Insumos\InsumoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditInsumo extends EditRecord
{
    protected static string $resource = InsumoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    protected function afterSave(): void
    {
        $insumo = $this->record; // el insumo que acabamos de editar

        if ($insumo->cantidad <= $insumo->stock_minimo) {
            Notification::make()
                ->title('⚠️ Stock bajo')
                ->body("El insumo '{$insumo->nombre}' tiene solo {$insumo->cantidad} unidades.")
                
                ->warning()
                ->send();
        }
    }
}
