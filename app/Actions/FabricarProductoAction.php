<?php

namespace App\Actions;

use App\Models\Producto;
use App\Models\MovimientoInventario;
use Illuminate\Support\Facades\DB;
use Exception;

class FabricarProductoAction
{
    public static function execute(Producto $producto, int $cantidad): void{
        DB::transaction(function () use ($producto, $cantidad) {

            /*
            |--------------------------------------------------------------------------
            | VALIDAR INSUMOS
            |--------------------------------------------------------------------------
            */

            foreach ($producto->productoInsumos as $detalle) {

                $cantidadNecesaria =
                    $detalle->cantidad * $cantidad;

                if ($detalle->insumo->cantidad < $cantidadNecesaria) {

                    throw new Exception(
                        'Stock insuficiente de: ' .
                        $detalle->insumo->nombre
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | DESCONTAR INSUMOS
            |--------------------------------------------------------------------------
            */

            foreach ($producto->productoInsumos as $detalle) {

                $cantidadNecesaria =
                    $detalle->cantidad * $cantidad;

                $insumo = $detalle->insumo;

                $insumo->cantidad -= $cantidadNecesaria;

                $insumo->save();
            }

            /*
            |--------------------------------------------------------------------------
            | AUMENTAR STOCK PRODUCTO
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

                'tipo' => 'FABRICACION',

                'motivo' => 'fabricacion_producto',

                'cantidad' => $cantidad,

                'observaciones' =>
                    'Fabricación automática',
            ]);
        });
    }
}