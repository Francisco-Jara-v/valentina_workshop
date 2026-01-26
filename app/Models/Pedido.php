<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedido extends Model
{
    protected $fillable = [
        'cliente_nombre',
        'fecha_pedido',
        'descripcion',
        'total',
        'valor_venta',
        'ganancia',
        'estado',
    ];

    protected static function booted()
    {
        static::created(function ($pedido) {
    
            foreach ($pedido->detalles as $detalle) {
                $insumo = $detalle->insumo;
    
                if (!$insumo) {
                    continue;
                }
    
                // Descontar stock
                $insumo->decrement('cantidad', $detalle->cantidad);
            }
        });
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }
}
