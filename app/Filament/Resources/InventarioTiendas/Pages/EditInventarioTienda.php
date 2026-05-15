<?php

namespace App\Filament\Resources\InventarioTiendas\Pages;

use App\Filament\Resources\InventarioTiendas\InventarioTiendaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInventarioTienda extends EditRecord
{
    protected static string $resource = InventarioTiendaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
