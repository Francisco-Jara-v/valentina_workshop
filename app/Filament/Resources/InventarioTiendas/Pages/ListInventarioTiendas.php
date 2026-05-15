<?php

namespace App\Filament\Resources\InventarioTiendas\Pages;

use App\Filament\Resources\InventarioTiendas\InventarioTiendaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInventarioTiendas extends ListRecords
{
    protected static string $resource = InventarioTiendaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
