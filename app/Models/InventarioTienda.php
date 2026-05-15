<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarioTienda extends Model
{
    protected $table = 'inventario_tienda';

    protected $fillable = [
        'producto_id',
        'cantidad_enviada',
        'cantidad_actual',
        'cantidad_vendida',
        'cantidad_devuelta',
        'ubicacion',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
