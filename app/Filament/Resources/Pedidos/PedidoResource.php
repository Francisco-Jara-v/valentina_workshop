<?php

namespace App\Filament\Resources\Pedidos;

use App\Filament\Resources\Pedidos\Pages\CreatePedido;
use App\Filament\Resources\Pedidos\Pages\EditPedido;
use App\Filament\Resources\Pedidos\Pages\ListPedidos;
use App\Filament\Resources\Pedidos\Schemas\PedidoForm;
use App\Filament\Resources\Pedidos\Tables\PedidosTable;
use App\Models\Pedido;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class PedidoResource extends Resource
{
    protected static ?string $model = Pedido::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Pedido';

    public static function form(Schema $schema): Schema
    {
        return PedidoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PedidosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    protected static function afterCreate($record): void
    {
        DB::transaction(function () use ($record) {

            foreach ($record->detalles as $detalle) {

                $insumo = $detalle->insumo;

                // 1️⃣ Descontar stock
                $insumo->cantidad -= $detalle->cantidad;
                $insumo->save();

                // 2️⃣ Verificar stock bajo
                if (
                    $insumo->cantidad <= $insumo->stock_minimo &&
                    ! Cache::has("stock_alert_{$insumo->id}")
                ) {
                    Notification::make()
                        ->title('⚠️ Stock bajo')
                        ->body("{$insumo->nombre} tiene solo {$insumo->cantidad} unidades.")
                        ->danger()
                        ->send();

                    // 3️⃣ Evitar spam por 12 horas
                    Cache::put(
                        "stock_alert_{$insumo->id}",
                        true,
                        now()->addHours(12)
                    );
                }
            }
        });
    }
    public static function getPages(): array
    {
        return [
            'index' => ListPedidos::route('/'),
            'create' => CreatePedido::route('/create'),
            'edit' => EditPedido::route('/{record}/edit'),
        ];
    }
}
