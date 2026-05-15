<?php

namespace App\Actions;

use App\Models\InventarioTienda;
use App\Models\MovimientoInventario;
use App\Models\Producto;

use Illuminate\Support\Facades\DB;
use Exception;

class RegistrarVentaAction
{
    public static function execute(
        Producto $producto,
        int $cantidad
    ): void {

        DB::transaction(function () use ($producto, $cantidad) {

            /*
            |--------------------------------------------------------------------------
            | INVENTARIO TIENDA
            |--------------------------------------------------------------------------
            */

            $inventario = $producto->inventarioTienda;

            if (! $inventario) {

                throw new Exception(
                    'El producto no existe en tienda'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | VALIDAR STOCK
            |--------------------------------------------------------------------------
            */

            if ($inventario->cantidad_actual < $cantidad) {

                throw new Exception(
                    'Stock insuficiente en tienda'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | DESCONTAR STOCK
            |--------------------------------------------------------------------------
            */

            $inventario->cantidad_actual -= $cantidad;

            /*
            |--------------------------------------------------------------------------
            | AUMENTAR VENDIDOS
            |--------------------------------------------------------------------------
            */

            $inventario->cantidad_vendida += $cantidad;

            $inventario->save();

            /*
            |--------------------------------------------------------------------------
            | MOVIMIENTO
            |--------------------------------------------------------------------------
            */

            MovimientoInventario::create([

                'producto_id' => $producto->id,

                'tipo' => 'venta',

                'motivo' => 'venta_tienda',

                'cantidad' => $cantidad,

                'observaciones' =>
                    'Venta registrada desde Filament',
            ]);
        });
    }
}