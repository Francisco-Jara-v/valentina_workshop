<?php

namespace App\Actions;

use App\Models\Producto;
use App\Models\InventarioTienda;
use App\Models\MovimientoInventario;
use Illuminate\Support\Facades\DB;
use Exception;

class TrasladarTiendaAction
{
    public static function execute(
        Producto $producto,
        int $cantidad
    ): void {

        DB::transaction(function () use ($producto, $cantidad) {

            /*
            |--------------------------------------------------------------------------
            | VALIDAR STOCK
            |--------------------------------------------------------------------------
            */

            if ($producto->stock_taller < $cantidad) {

                throw new Exception(
                    'Stock insuficiente en taller'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | DESCONTAR STOCK TALLER
            |--------------------------------------------------------------------------
            */

            $producto->stock_taller -= $cantidad;

            $producto->save();

            /*
            |--------------------------------------------------------------------------
            | INVENTARIO TIENDA
            |--------------------------------------------------------------------------
            */

            $inventario = InventarioTienda::firstOrCreate(
                [
                    'producto_id' => $producto->id,
                ],
                [
                    'cantidad_actual' => 0,
                    'cantidad_enviada' => 0,
                    'cantidad_vendida' => 0,
                    'cantidad_devuelta' => 0,
                    'ubicacion' => 'Tienda',
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | AUMENTAR STOCK TIENDA
            |--------------------------------------------------------------------------
            */

            $inventario->cantidad_actual += $cantidad;

            $inventario->cantidad_enviada += $cantidad;

            $inventario->save();

            /*
            |--------------------------------------------------------------------------
            | MOVIMIENTO
            |--------------------------------------------------------------------------
            */

            MovimientoInventario::create([

                'producto_id' => $producto->id,

                'tipo' => 'SALIDA',

                'motivo' => 'envio_tienda',

                'cantidad' => $cantidad,

                'observaciones' =>
                    'Envío a tienda',
            ]);
        });
    }
}