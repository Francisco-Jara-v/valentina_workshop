<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoInventario extends Model
{
    protected $table = 'movimiento_inventario';
    protected $fillable = [
        'producto_id',
        'tipo',
        'motivo',
        'cantidad',
        'observaciones',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    
}
