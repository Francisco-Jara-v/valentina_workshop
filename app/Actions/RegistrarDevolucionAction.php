<?php

namespace App\Actions;

use App\Models\MovimientoInventario;
use App\Models\Producto;

use Illuminate\Support\Facades\DB;
use Exception;

class RegistrarDevolucionAction
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
            | DESCONTAR STOCK TIENDA
            |--------------------------------------------------------------------------
            */

            $inventario->cantidad_actual -= $cantidad;

            /*
            |--------------------------------------------------------------------------
            | AUMENTAR DEVUELTOS
            |--------------------------------------------------------------------------
            */

            $inventario->cantidad_devuelta += $cantidad;

            $inventario->save();

            /*
            |--------------------------------------------------------------------------
            | DEVOLVER A TALLER
            |--------------------------------------------------------------------------
            */

            $producto->stock_taller += $cantidad;

            $producto->save();

            /*
            |--------------------------------------------------------------------------
            | MOVIMIENTO
            |--------------------------------------------------------------------------
            */

            MovimientoInventario::create([

                'producto_id' => $producto->id,

                'tipo' => 'devolucion',

                'motivo' => 'devolucion_tienda',

                'cantidad' => $cantidad,

                'observaciones' =>
                    'Devolución desde tienda',
            ]);
        });
    }
}